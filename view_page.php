<?php
include 'config/db.php';
session_start();

$pageID = isset($_GET['page_id']) ? intval($_GET['page_id']) : 0;


$stmt = $conn->prepare("SELECT Pages.*, Images.FilePath AS ImagePath FROM Pages LEFT JOIN Images ON Pages.ImageID = Images.ImageID WHERE PageID = :page_id");
$stmt->execute(['page_id' => $pageID]);
$page = $stmt->fetch();

if (!$page) {
    echo "Page not found.";
    exit();
}



$message = '';
$userInput = [
    'username' => '',
    'comment' => ''
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = trim($_POST['username']);
    $commentText = trim($_POST['comment']);
    $captchaInput = trim($_POST['captcha']);

    
    $userInput['username'] = $userName;
    $userInput['comment'] = $commentText;

    if ($captchaInput === $_SESSION['captcha']) {
        if (!empty($userName) && !empty($commentText)) {
            $stmt = $conn->prepare("INSERT INTO Comments (PageID, UserName, CommentText, CreatedAt, IsVisible) VALUES (:page_id, :username, :comment, NOW(), TRUE)");
            $stmt->execute(['page_id' => $pageID, 'username' => $userName, 'comment' => $commentText]);
            $message = "Your comment has been submitted successfully!";
            $userInput = [];
        } else {
            $message = "Please fill out all fields.";
        }
    } else {
        $message = "Invalid CAPTCHA. Please try again.";
    }

    unset($_SESSION['captcha']);
}
?>

<?php include 'includes/header.php'; ?>
<style>
    .container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
}

.comment-form {
    margin-bottom: 20px;
    border: 1px solid #ddd;
    padding: 15px;
    border-radius: 5px;
    background: #f9f9f9;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

.btn-submit {
    padding: 10px 15px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    display: inline-block;
}

.btn-submit:hover {
    background-color: #45a049;
}

.comments-list {
    margin-top: 20px;
}

.comment {
    border-bottom: 1px solid #ddd;
    padding: 10px 0;
}

.comment-author {
    font-weight: bold;
    color: #333;
}

.comment-text {
    margin: 10px 0;
    font-size: 14px;
    color: #555;
}

.comment-date {
    font-size: 12px;
    color: #999;
}

.no-comments {
    color: #777;
    font-style: italic;
}
</style>
<div class="container">
    <article>
        <h1><?php echo htmlspecialchars($page['Title']); ?></h1>
      <?php 
if (!empty($page['ImagePath'])): 
 
    
    $sanitizedPath = str_replace('../', '', $page['ImagePath']); 
?>
    <img src="<?php echo $baseUrl . '/' . htmlspecialchars($sanitizedPath); ?>" alt="Page Image" width="400">
<?php else: ?>
    No Image
<?php endif; ?>
        <p><?php echo nl2br(htmlspecialchars($page['Content'])); ?></p>
    </article>

    <section class="comments-section">
        <h2>Comments</h2>


        
        <form method="POST" class="comment-form">
            <div class="form-group">
                <label for="username">Name</label>
                <input type="text" id="username" name="username" placeholder="Enter your name" value="<?php echo htmlspecialchars($userInput['username'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="comment">Comment</label>
                <textarea id="comment" name="comment" placeholder="Write your comment here" required><?php echo htmlspecialchars($userInput['comment'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="captcha">CAPTCHA</label>
                <img src="generate_captcha.php" alt="CAPTCHA Image">
                <input type="text" id="captcha" name="captcha" placeholder="Enter CAPTCHA" required>
            </div>
            <button type="submit" class="btn-submit">Submit Comment</button>
        </form>

        
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>


        
        <div class="comments-list">
            <?php
            $stmt = $conn->prepare("SELECT * FROM Comments WHERE PageID = :page_id AND IsVisible = TRUE ORDER BY CreatedAt DESC");
            $stmt->execute(['page_id' => $pageID]);
            $comments = $stmt->fetchAll();
            ?>

            <?php if (count($comments) > 0): ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <strong class="comment-author"><?php echo htmlspecialchars($comment['UserName']); ?></strong>
                        <p class="comment-text"><?php echo nl2br(htmlspecialchars($comment['CommentText'])); ?></p>
                        <small class="comment-date">Posted on <?php echo htmlspecialchars($comment['CreatedAt']); ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-comments">No comments yet. Be the first to comment!</p>
            <?php endif; ?>
        </div>
    </section>
</div>
<?php include 'includes/footer.php'; ?>
