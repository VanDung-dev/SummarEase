import mysql.connector
from mysql.connector import Error
import os
import logging
from typing import Dict, Any

logger = logging.getLogger(__name__)

def get_db_connection():
    """
    Tạo và trả về kết nối đến cơ sở dữ liệu MySQL
    """
    try:
        connection = mysql.connector.connect(
            host=os.getenv('DB_HOST', '127.0.0.1'),
            port=os.getenv('DB_PORT', 3306),
            database=os.getenv('DB_DATABASE', 'summarease'),
            user=os.getenv('DB_USERNAME', 'root'),
            password=os.getenv('DB_PASSWORD', '')
        )
        return connection
    except Error as e:
        logger.error(f"Lỗi kết nối cơ sở dữ liệu: {e}")
        return None

def save_summary_to_db(summary_data: Dict[str, Any], document_data: Dict[str, Any]):
    """
    Lưu kết quả tóm tắt và tài liệu vào cơ sở dữ liệu
    
    Args:
        summary_data: Dữ liệu tóm tắt bao gồm summary_text, ratio, keywords, etc.
        document_data: Dữ liệu tài liệu bao gồm content, title, file_name, file_type, user_id
        
    Returns:
        bool: True nếu lưu thành công, False nếu có lỗi
    """
    connection = get_db_connection()
    if not connection:
        return False
    
    try:
        cursor = connection.cursor()
        
        # Bắt đầu transaction
        connection.start_transaction()
        
        # 1. Thêm bản ghi vào bảng documents
        insert_document_query = """
        INSERT INTO documents (user_id, title, file_name, file_type, content, uploaded_at)
        VALUES (%s, %s, %s, %s, %s, NOW())
        """
        document_values = (
            document_data.get('user_id', 1),  # Mặc định user_id = 1 nếu không có
            document_data.get('title', 'Untitled'),
            document_data.get('file_name'),
            document_data.get('file_type'),
            document_data.get('content')
        )
        
        cursor.execute(insert_document_query, document_values)
        document_id = cursor.lastrowid
        
        # 2. Thêm bản ghi vào bảng summaries
        # Chỉ sử dụng highlighted_summary để lưu vào cơ sở dữ liệu
        insert_summary_query = """
        INSERT INTO summaries (document_id, summary_text, summary_ratio, created_at)
        VALUES (%s, %s, %s, NOW())
        """
        summary_values = (
            document_id,
            summary_data['highlighted_summary'],  # Lấy trực tiếp giá trị highlighted_summary
            summary_data.get('ratio')
        )
        
        cursor.execute(insert_summary_query, summary_values)
        summary_id = cursor.lastrowid
        
        # 3. Thêm keywords vào bảng keywords
        if 'keywords' in summary_data and summary_data['keywords']:
            insert_keyword_query = """
            INSERT INTO keywords (summary_id, keyword_text, weight, is_auto_generated)
            VALUES (%s, %s, %s, %s)
            """
            keyword_data = []
            for i, keyword in enumerate(summary_data['keywords']):
                weight = 1.0 - (i * 0.1)  # Trọng số giảm dần theo thứ tự xuất hiện
                keyword_data.append((summary_id, keyword, weight, 1))
            
            cursor.executemany(insert_keyword_query, keyword_data)
        
        # 4. Commit transaction
        connection.commit()
        logger.info(f"Đã lưu thành công tài liệu ID {document_id} và bản tóm tắt ID {summary_id}")
        return True
        
    except Error as e:
        # Rollback trong trường hợp có lỗi
        connection.rollback()
        logger.error(f"Lỗi khi lưu dữ liệu vào cơ sở dữ liệu: {e}")
        return False
    except KeyError as e:
        # Xử lý lỗi khi thiếu key trong dictionary
        connection.rollback()
        logger.error(f"Thiếu key trong dữ liệu tóm tắt: {e}")
        return False
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()