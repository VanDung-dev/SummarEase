import pytest
import json
import os
from unittest.mock import patch
try:
    from app.main import app
except ImportError:
    raise ImportError("Không thể nhập 'app' từ 'app.main'. Vui lòng kiểm tra app/main.py.")

@pytest.fixture
def client():
    """Tạo client kiểm thử cho ứng dụng Flask."""
    app.config['TESTING'] = True
    with app.test_client() as client:
        yield client


def test_summarize_text_success(client):
    """Kiểm tra tuyến /summarize với yêu cầu hợp lệ."""
    with patch('app.routes.clean_text', return_value="Văn bản đã được làm sạch") as mock_clean, \
            patch('app.routes.textrank_summarize', return_value="Tóm tắt văn bản") as mock_summarize:
        response = client.post(
            '/summarize',
            data=json.dumps({
                'text': 'Đây là văn bản cần tóm tắt',
                'ratio': 0.2,
                'language': 'vietnamese'
            }),
            content_type='application/json'
        )

        assert response.status_code == 200
        data = response.get_json()
        assert data['status'] == 'success'
        assert data['summary'] == 'Tóm tắt văn bản'
        assert data['ratio'] == 0.2
        assert data['language'] == 'vietnamese'
        mock_clean.assert_called_once_with('Đây là văn bản cần tóm tắt')
        mock_summarize.assert_called_once()


def test_summarize_text_empty_input(client):
    """Kiểm tra tuyến /summarize với văn bản rỗng."""
    response = client.post(
        '/summarize',
        data=json.dumps({
            'text': '',
            'ratio': 0.2,
            'language': 'vietnamese'
        }),
        content_type='application/json'
    )

    assert response.status_code == 400
    data = response.get_json()
    assert data['error'] == 'Nội dung văn bản không được để trống'


def test_summarize_text_invalid_language(client):
    """Kiểm tra tuyến /summarize với ngôn ngữ không hợp lệ."""
    response = client.post(
        '/summarize',
        data=json.dumps({
            'text': 'Đây là văn bản',
            'ratio': 0.2,
            'language': 'french'
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


def test_summarize_file_success(client):
    """Kiểm tra tuyến /summarize-file với tệp hợp lệ."""
    with patch('app.routes.extract_text', return_value="Văn bản từ tệp") as mock_extract, \
            patch('app.routes.clean_text', return_value="Văn bản đã làm sạch") as mock_clean, \
            patch('app.routes.textrank_summarize', return_value="Tóm tắt từ tệp") as mock_summarize, \
            patch('os.path.exists', return_value=True), \
            patch('os.remove') as mock_remove:
        response = client.post(
            '/summarize-file',
            data={
                'file': (open(os.devnull, 'rb'), 'test.txt'),
                'ratio': '0.2',
                'language': 'vietnamese'
            },
            content_type='multipart/form-data'
        )

        assert response.status_code == 200
        data = response.get_json()
        assert data['status'] == 'success'
        assert data['summary'] == 'Tóm tắt từ tệp'
        assert data['filename'] == 'test.txt'
        mock_extract.assert_called_once()
        mock_clean.assert_called_once_with('Văn bản từ tệp')
        mock_summarize.assert_called_once()
        mock_remove.assert_called_once()


def test_summarize_file_no_file(client):
    """Kiểm tra tuyến /summarize-file khi không có tệp."""
    response = client.post(
        '/summarize-file',
        data={},
        content_type='multipart/form-data'
    )

    assert response.status_code == 400
    data = response.get_json()
    assert data['error'] == 'Không tìm thấy tệp trong yêu cầu'


def test_summarize_file_invalid_extension(client):
    """Kiểm tra tuyến /summarize-file với định dạng tệp không hỗ trợ."""
    with patch('app.routes.os.path.exists', return_value=True), \
            patch('os.remove') as mock_remove:
        response = client.post(
            '/summarize-file',
            data={
                'file': (open(os.devnull, 'rb'), 'test.xyz'),
                'ratio': '0.2',
                'language': 'vietnamese'
            },
            content_type='multipart/form-data'
        )

        assert response.status_code == 400
        data = response.get_json()
        assert data['error'] == 'Định dạng tệp không được hỗ trợ'
        mock_remove.assert_called_once()