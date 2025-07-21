Hướng dẫn bật Password Reset với Email Verification

Password Reset: mặc định bật sẵn trong Laravel. Cần chỉnh mục EMAIL trong file cấu hình .env thành một dịch vụ thiệt. Ko đc dùng mailpit/mailtrap. Google Mail thì ko cho app dùng acc mail do vấn đề an ninh. Nhma khi tui thử App Password thì nó cũng ko cho nên nguy cơ cao phải đăng ký 1 dịch vụ khác

Email Verification: trong app\Models\User.php cần chỉnh email như trên VÀ gỡ comment 2 dòng:

//use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable// implements MustVerifyEmail

Dịch vụ Mail thì tìm đồ có free tier, đừng có free trial. Cái nào được quảng cáo là cho mục đích gửi Transactional Email á
