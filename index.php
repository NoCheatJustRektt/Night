<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="/">
        <meta charset="UTF-8">
        <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
        <link rel="stylesheet" href="public/css/chatbot.css">
        <script src="public/js/chatbot.js" defer></script>
        <link rel="stylesheet" href="public/css/snake.css">
        <script src="public/js/snake.js" defer></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        

    </head>
    <body>
        <?php include 'src/view/chatbot-widget.php'; ?>
        <?php include 'src/view/snake-widget.php'; ?>
        <button onclick="window.location.href = 'src/model/tetris.php'">lien tetris tmp</button>
    </body>
</html>
