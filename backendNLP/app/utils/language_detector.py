import re

# Các từ đặc trưng cho tiếng Việt
VIETNAMESE_WORDS = {
    'và', 'của', 'trong', 'đó', 'với', 'cho', 'được', 'đã', 'những', 'các', 
    'nhưng', 'có', 'là', 'rằng', 'này', 'ấy', 'ra', 'rồi', 'như', 'bởi', 
    'thì', 'mà', 'nên', 'lại', 'lên', 'xuống', 'về', 'ở', 'tại', 'theo', 
    'từ', 'đến', 'bằng', 'cùng', 'nhau', 'một', 'hai', 'ba', 'bốn', 'năm',
    'sáu', 'bảy', 'tám', 'chín', 'mười', 'người', 'ngày', 'năm', 'tháng', 
    'giờ', 'phút', 'giây', 'khi', 'lúc', 'nếu', 'thì', 'sẽ', 'hãy', 'đừng',
    'chớ', 'đi', 'điều', 'việc', 'công việc', 'thế nào', 'sao', 'tại sao',
    'ai', 'gì', 'nào', 'ơi', 'à', 'á', 'ạ', 'ơi', 'ư', 'hử', 'huh', 'nha'
}

# Các từ đặc trưng cho tiếng Anh
ENGLISH_WORDS = {
    'the', 'be', 'to', 'of', 'and', 'a', 'in', 'that', 'have', 'i', 'it', 
    'for', 'not', 'on', 'with', 'he', 'as', 'you', 'do', 'at', 'this', 'but',
    'his', 'by', 'from', 'they', 'we', 'say', 'her', 'she', 'or', 'an', 'will',
    'my', 'one', 'all', 'would', 'there', 'their', 'what', 'so', 'up', 'out',
    'if', 'about', 'who', 'get', 'which', 'go', 'me', 'when', 'make', 'can',
    'like', 'time', 'no', 'just', 'him', 'know', 'take', 'people', 'into',
    'year', 'your', 'good', 'some', 'could', 'them', 'see', 'other', 'than',
    'then', 'now', 'look', 'only', 'come', 'its', 'over', 'think', 'also',
    'back', 'after', 'use', 'two', 'how', 'our', 'work', 'first', 'well',
    'way', 'even', 'new', 'want', 'because', 'any', 'these', 'give', 'day',
    'most', 'us',
    # Từ mượn tiếng Pháp
    'résumé', 'café', 'naïve', 'fiancée', 'fiancé', 'exposé', 'touché', 'protégé', 'décor', 'façade', 
    'rôle', 'élite', 'entrée', 'à la carte', 'déjà vu', 'voilà',
    # Từ mượn tiếng Tây Ban Nha
    'jalapeño', 'piñata', 'señor', 'mañana'
}

def detect_language(text):
    """
    Phát hiện ngôn ngữ của văn bản dựa trên các từ đặc trưng.
    
    Args:
        text (str): Văn bản cần phát hiện ngôn ngữ
        
    Returns:
        str: 'vietnamese' hoặc 'english'
    """
    # Chuyển văn bản về chữ thường và tách từ
    text = text.lower()
    
    # Loại bỏ các ký tự đặc biệt và số
    text = re.sub(r'[^a-zA-Zàáâãèéêìíòóôõùúýăđĩũơưạảấầẩẫậắằẳẵặẹẻẽếềểễệỉịọỏốồổỗộớờởỡợụủứừửữựỳỵỷỹ\s]', ' ', text)
    
    # Tách từ
    words = text.split()
    
    # Đếm số từ trùng với các từ đặc trưng
    vietnamese_count = sum(1 for word in words if word in VIETNAMESE_WORDS)
    english_count = sum(1 for word in words if word in ENGLISH_WORDS)
    
    # Nếu không có từ nào trùng, sử dụng độ dài trung bình của từ để ước lượng
    if vietnamese_count == 0 and english_count == 0:
        # Tính độ dài trung bình của từ
        if len(words) > 0:
            avg_word_length = sum(len(word) for word in words) / len(words)
            # Tiếng Việt thường có từ dài hơn do dấu thanh
            if avg_word_length > 5:
                return 'vietnamese'
            else:
                return 'english'
        else:
            return 'english'  # Mặc định là tiếng Anh
    
    # Trả về ngôn ngữ có nhiều từ trùng hơn
    if vietnamese_count > english_count:
        return 'vietnamese'
    else:
        return 'english'