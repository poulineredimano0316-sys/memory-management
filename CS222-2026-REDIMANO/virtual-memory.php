<?php
$partitions = [223, 300, 689, 1034, 4302];
$allocation_result = "";
$virtual_memory = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $file_size = (int)$_POST["file_size"];
    $allocated = false;

    foreach ($partitions as $partition) {
        if ($file_size <= $partition) {
            $allocation_result = "File of {$file_size}KB placed in Virtual Memory (Partition {$partition}KB)";
            $virtual_memory[] = ["size" => $file_size, "partition" => $partition];
            $allocated = true;
            break;
        }
    }

    if (!$allocated) {
        $allocation_result = "File of {$file_size}KB could not be placed: Not enough space in any partition.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Allocation</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Archivo+Black&family=Inter:wght@400;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #e0e7ff 0%, #f3e8ff 40%, #d1fae5 70%, #fce7f3 100%);
            font-family: 'Inter', sans-serif;
            color: #312e81;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: clamp(40px, 8vh, 80px) 20px;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        .blob {
            position: absolute;
            z-index: -1;
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            filter: blur(8px);
            box-shadow: inset 10px -10px 20px rgba(255, 255, 255, 0.4), 
                        20px 20px 40px rgba(0, 0, 0, 0.05);
            animation: morph 10s ease-in-out infinite alternate;
        }
        .blob1 { width: clamp(200px, 40vw, 400px); height: clamp(200px, 40vw, 400px); top: -100px; right: -50px; background: linear-gradient(135deg, #a5b4fc, #fbcfe8); }
        .blob2 { width: clamp(150px, 35vw, 350px); height: clamp(150px, 35vw, 350px); bottom: -80px; left: -50px; background: linear-gradient(135deg, #6ee7b7, #93c5fd); animation-delay: -2s; }

        @keyframes morph {
            0% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            50% { border-radius: 50% 50% 30% 70% / 50% 30% 70% 50%; }
            100% { border-radius: 70% 30% 50% 50% / 30% 70% 30% 70%; }
        }

        h1 {
            font-family: 'Archivo Black', sans-serif;
            font-size: clamp(1.8rem, 6vw, 3.5rem);
            text-transform: uppercase;
            letter-spacing: -1px;
            margin-bottom: 30px;
            text-align: center;
            background: linear-gradient(to bottom, #312e81, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.1;
        }

        form {
            margin-bottom: 40px;
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
            padding: clamp(20px, 5vw, 30px);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.6);
            text-align: center;
            z-index: 10;
            width: 100%;
            max-width: 500px;
        }

        label {
            font-weight: 700;
            color: #4338ca;
            display: block;
            margin-bottom: 15px;
            font-size: clamp(0.9rem, 2vw, 1rem);
        }

        input[type="text"] {
            padding: 12px 20px;
            border: 2px solid rgba(67, 56, 202, 0.1);
            border-radius: 100px;
            width: 100%;
            max-width: 280px;
            background: white;
            color: #312e81;
            font-size: 1rem;
            outline: none;
            transition: 0.3s;
            text-align: center;
            font-weight: 600;
        }

        input[type="text"]:focus {
            border-color: #6366f1;
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.2);
        }

        button {
            padding: 12px 35px;
            background: #312e81;
            color: white;
            border: none;
            font-size: 1rem;
            font-weight: 700;
            border-radius: 100px;
            cursor: pointer;
            transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-top: 15px;
            width: 100%;
            max-width: 280px;
        }

        button:hover {
            background: #4338ca;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(49, 46, 129, 0.2);
        }

        .result {
            font-size: clamp(0.9rem, 2.5vw, 1.1rem);
            margin-bottom: 25px;
            padding: 15px 25px;
            background: #ffffff;
            border-radius: clamp(20px, 10vw, 100px);
            color: #166534;
            font-weight: 700;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border: 1px solid rgba(74, 222, 128, 0.3);
            text-align: center;
            max-width: 900px;
        }

        .container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            width: 100%;
            max-width: 1000px;
            z-index: 10;
        }

        .box {
            flex: 1;
            min-width: 280px;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(15px);
            padding: clamp(20px, 4vw, 25px);
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.7);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        }

        .box h2 {
            font-family: 'Archivo Black', sans-serif;
            font-size: clamp(1.2rem, 3vw, 1.5rem);
            margin-bottom: 20px;
            color: #312e81;
            text-transform: uppercase;
            letter-spacing: -1px;
        }

        .partition {
            padding: 15px;
            margin: 10px 0;
            border-radius: 16px;
            background: #ffffff;
            color: #475569;
            font-weight: 700;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            transition: 0.3s;
            font-size: clamp(0.85rem, 2vw, 1rem);
            word-break: break-all;
        }

        .filled {
            background: linear-gradient(135deg, #ef4444, #f87171);
            color: #ffffff;
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.2);
        }

        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            font-weight: 700;
            background: #ffffff;
            color: #312e81;
            border-radius: 100px;
            text-decoration: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: 0.3s;
            z-index: 100;
            border: 1px solid rgba(0,0,0,0.05);
            font-size: 0.9rem;
        }

        .back-button:hover {
            background: #312e81;
            color: white;
            transform: translateX(-5px);
        }

        /* Mobile Adjustments */
        @media (max-width: 600px) {
            body { padding-top: 80px; } /* Space for fixed back button */
            .back-button { top: 15px; left: 15px; padding: 8px 16px; font-size: 0.8rem; }
            .box { min-width: 100%; }
        }
    </style>
</head>
<body>

    <div class="blob blob1"></div>
    <div class="blob blob2"></div>

    <a href="indexpage.php" class="back-button">
        &larr; Back
    </a>

    <h1>Virtual Memory Management</h1>

    <?php if ($allocation_result): ?>
        <div class="result"><?= $allocation_result ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Enter File Size (KB):</label>
        <input 
            type="text" 
            name="file_size" 
            required 
            pattern="\d+" 
            inputmode="numeric"
            placeholder="e.g. 512"
        >
        <br>
        <button type="submit">Place File</button>
    </form>

    <div class="container">
        <div class="box">
            <h2>Primary Memory</h2>
            <?php if (!empty($virtual_memory)): ?>
                <div class="partition filled">
                    File: <?= $virtual_memory[0]["size"] ?>KB
                </div>
            <?php else: ?>
                <div class="partition">No file placed</div>
            <?php endif; ?>
        </div>

        <div class="box">
            <h2>Virtual Memory</h2>
            <?php foreach ($partitions as $partition): ?>
                <?php
                    $filled = false;
                    $display = "{$partition}KB";
                    if (!empty($virtual_memory) && $virtual_memory[0]["partition"] == $partition) {
                        $filled = true;
                        $display = "{$virtual_memory[0]['size']}KB / {$partition}KB";
                    }
                ?>
                <div class="partition <?= $filled ? 'filled' : '' ?>">
                    <?= $display ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>