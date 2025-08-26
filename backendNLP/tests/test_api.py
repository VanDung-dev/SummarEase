import pytest
import json
import os
import sys
from unittest.mock import patch
from pathlib import Path

# Thêm thư mục gốc vào PYTHONPATH
root_dir = Path(__file__).parent.parent
sys.path.insert(0, str(root_dir))

try:
    from app.main import app
except ImportError as e:
    print(f"Lỗi import: {e}")
    print(f"Current sys.path: {sys.path}")
    raise

@pytest.fixture
def client():
    """Tạo client kiểm thử cho ứng dụng Flask."""
    app.config['TESTING'] = True
    with app.test_client() as client:
        yield client


def test_summarize_text_success(client):
    """Kiểm tra tuyến /summarize với yêu cầu hợp lệ."""
    with patch('app.routes.clean_text', return_value="Văn bản đã được làm sạch") as mock_clean, \
            patch('app.routes.textrank_summarize', return_value={
                "summary": "Tóm tắt văn bản",
                "highlighted_summary": "**Tóm tắt** văn bản",
                "keywords": ["Tóm tắt"],
                "title": "Tiêu đề tóm tắt"
            }) as mock_summarize, \
            patch('app.routes.save_summary_to_db', return_value=True) as mock_save:
        response = client.post(
            '/summarize',
            data=json.dumps({
                'text': 'Đây là văn bản cần tóm tắt',
                'ratio': 0.2,
                'language': 'vietnamese',
                'user_id': 1
            }),
            content_type='application/json'
        )

        assert response.status_code == 200
        data = response.get_json()
        assert data['status'] == 'success'
        assert "**Tóm tắt**" in data['summary']  # Now returns highlighted_summary
        assert data['ratio'] == 0.2
        assert data['language'] == 'vietnamese'
        mock_clean.assert_called_once_with('Đây là văn bản cần tóm tắt')
        mock_summarize.assert_called_once()
        mock_save.assert_called_once()


def test_summarize_text_empty_input(client):
    """Kiểm tra tuyến /summarize với văn bản rỗng."""
    response = client.post(
        '/summarize',
        data=json.dumps({
            'text': '',
            'ratio': 0.2,
            'language': 'vietnamese',
            'user_id': 1
        }),
        content_type='application/json'
    )

    assert response.status_code == 400
    data = response.get_json()
    assert data['error'] == 'Nội dung văn bản không được để trống'


def test_summarize_text_missing_user_id(client):
    """Kiểm tra tuyến /summarize khi thiếu user_id."""
    response = client.post(
        '/summarize',
        data=json.dumps({
            'text': 'Đây là văn bản',
            'ratio': 0.2,
            'language': 'vietnamese'
            # Thiếu user_id
        }),
        content_type='application/json'
    )

    assert response.status_code == 400
    data = response.get_json()
    assert data['error'] == 'Thiếu user_id trong yêu cầu'


def test_summarize_text_invalid_language(client):
    """Kiểm tra tuyến /summarize với ngôn ngữ không hợp lệ."""
    response = client.post(
        '/summarize',
        data=json.dumps({
            'text': 'Đây là văn bản',
            'ratio': 0.2,
            'language': 'french',
            'user_id': 1
        }),
        content_type='application/json'
    )

    assert response.status_code == 400
    data = response.get_json()
    assert data['error'] == 'Ngôn ngữ không được hỗ trợ: french'


def test_summarize_text_invalid_json(client):
    """Kiểm tra tuyến /summarize với yêu cầu không phải JSON."""
    response = client.post(
        '/summarize',
        data='not json',
        content_type='text/plain'
    )

    assert response.status_code == 400
    data = response.get_json()
    assert data['error'] == 'Yêu cầu phải có Content-Type là application/json'


def test_summarize_files_success(client):
    """Kiểm tra tuyến /summarize-files với tệp hợp lệ."""
    with patch('app.routes.extract_text', return_value="Văn bản từ tệp") as mock_extract, \
            patch('app.routes.clean_text', return_value="Văn bản đã làm sạch") as mock_clean, \
            patch('app.routes.textrank_summarize', return_value={
                "summary": "Tóm tắt từ tệp",
                "highlighted_summary": "**Tóm tắt** từ tệp",
                "keywords": ["Tóm tắt"],
                "title": "Tiêu đề từ tệp"
            }) as mock_summarize, \
            patch('app.routes.save_summary_to_db', return_value=True) as mock_save, \
            patch('os.path.exists', return_value=True), \
            patch('os.remove') as mock_remove:
        # Tạo file tạm giả lập
        with open('test.txt', 'w') as f:
            f.write('test content')

        try:
            with open('test.txt', 'rb') as f:
                response = client.post(
                    '/summarize-files',
                    data={
                        'files': (f, 'test.txt'),
                        'ratio': '0.2',
                        'language': 'vietnamese',
                        'user_id': '1'
                    },
                    content_type='multipart/form-data'
                )

            assert response.status_code == 200
            data = response.get_json()
            assert data['status'] == 'success'
            assert "**Tóm tắt**" in data['summary']  # Now returns highlighted_summary
            mock_extract.assert_called_once()
            mock_clean.assert_called_once_with('Văn bản từ tệp')
            mock_summarize.assert_called_once()
            mock_save.assert_called_once()
        finally:
            # Dọn dẹp file test
            os.remove('test.txt')


def test_summarize_files_no_file(client):
    """Kiểm tra tuyến /summarize-files khi không có tệp."""
    response = client.post(
        '/summarize-files',
        data={
            'user_id': '1'
        },
        content_type='multipart/form-data'
    )

    assert response.status_code == 400
    data = response.get_json()
    assert data['error'] == 'Không tìm thấy tệp trong yêu cầu'


def test_summarize_files_invalid_extension(client):
    """Kiểm tra tuyến /summarize-files với định dạng tệp không hỗ trợ."""
    # Tạo file test giả lập
    with open('test.xyz', 'w') as f:
        f.write('test content')

    try:
        with patch('app.routes.os.path.exists', return_value=True), \
                patch('os.remove') as mock_remove:
            with open('test.xyz', 'rb') as f:
                response = client.post(
                    '/summarize-files',
                    data={
                        'files': (f, 'test.xyz'),
                        'ratio': '0.2',
                        'language': 'vietnamese',
                        'user_id': '1'
                    },
                    content_type='multipart/form-data'
                )

            assert response.status_code == 400
            data = response.get_json()
            assert 'Định dạng tệp không được hỗ trợ' in data['error']
            mock_remove.assert_called_once()
    finally:
        # Đảm bảo dọn dẹp file test
        if os.path.exists('test.xyz'):
            os.remove('test.xyz')