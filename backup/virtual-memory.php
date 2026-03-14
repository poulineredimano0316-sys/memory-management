<<<<<<< HEAD:backup/virtual-memory.php
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
        $allocation_result = "File of {$file_size}KB could not be placed: Not enough space.";
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
        /* FONTS & COLORS LANG ANG BINAGO */
        @import url('https://fonts.googleapis.com/css2?family=Archivo+Black&family=Inter:wght@400;600;700&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            text-align: center;
            padding: 100px 20px 60px; 
            /* DARK GRADIENT BACKGROUND */
            background-color: #0d0628;
            background: radial-gradient(circle at 50% 0%, #3b117a 0%, #150833 45%, #0d0628 100%);
            color: #ffffff;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* NEON GLOW EFFECT */
        body::before {
            content: ''; position: fixed; top: 10%; left: 50%; width: 100%; height: 100%;
            background: radial-gradient(circle, rgba(0, 242, 255, 0.08) 0%, rgba(255, 0, 212, 0.05) 40%, transparent 70%);
            transform: translateX(-50%); z-index: -1; pointer-events: none;
        }

        /* BACK BUTTON SA BABA (PARA SAME SA CPU PAGES) */
        .back-button { 
            position: fixed; bottom: 30px; left: 30px; padding: 12px 25px; 
            font-weight: 700; background: rgba(255,255,255,0.08); color: white; 
            border-radius: 100px; text-decoration: none; backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1); transition: 0.3s; z-index: 1100;
        }
        .back-button:hover { background: #00f2ff; color: #000; transform: translateY(-3px); }

        h1 { 
            font-family: 'Archivo Black', sans-serif; font-size: clamp(2rem, 6vw, 3.5rem); 
            margin-bottom: 40px; text-transform: uppercase; letter-spacing: -1px; 
        }

        form { 
            margin-bottom: 40px; background: rgba(255, 255, 255, 0.03); 
            padding: 40px; border-radius: 30px; border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px); box-shadow: 0 20px 50px rgba(0,0,0,0.4);
            width: 100%; max-width: 500px;
        }

        label { font-weight: 700; color: #00f2ff; display: block; margin-bottom: 15px; }

        input[type="text"] { 
            padding: 16px; margin: 10px 0; width: 100%; font-size: 1rem; 
            border: 1px solid rgba(255,255,255,0.1); border-radius: 15px; 
            background: rgba(0,0,0,0.3); color: white; font-weight: 600; outline: none; 
            text-align: center;
        }
        
        button { 
            background: #00f2ff; color: #000; border: none; cursor: pointer; 
            font-weight: 800; text-transform: uppercase; margin-top: 25px; 
            padding: 16px; border-radius: 15px; width: 100%;
            box-shadow: 0 5px 20px rgba(0, 242, 255, 0.3); transition: 0.3s;
        }
        button:hover { transform: translateY(-3px); filter: brightness(1.2); }

        .result { 
            font-size: 1rem; margin-bottom: 25px; padding: 15px 30px; 
            background: rgba(0, 242, 255, 0.1); border-radius: 100px; 
            color: #00f2ff; font-weight: 700; border: 1px solid rgba(0, 242, 255, 0.3);
        }

        .container { display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; width: 100%; max-width: 1000px; }

        .box { 
            flex: 1; min-width: 280px; background: rgba(255, 255, 255, 0.03); 
            padding: 30px; border-radius: 30px; border: 1px solid rgba(255, 255, 255, 0.08); 
        }

        .box h2 { 
            font-family: 'Archivo Black', sans-serif; font-size: 1.3rem; 
            margin-bottom: 20px; color: #00f2ff; text-transform: uppercase; 
        }

        .partition { 
            padding: 15px; margin: 10px 0; border-radius: 12px; 
            background: rgba(255, 255, 255, 0.05); color: rgba(255,255,255,0.7); 
            font-weight: 700; border: 1px solid rgba(255,255,255,0.05);
        }

        .filled { 
            background: linear-gradient(135deg, #00f2ff, #a855f7); 
            color: #000; box-shadow: 0 5px 15px rgba(0, 242, 255, 0.3); 
            border: none;
        }

    </style>
</head>
<body>

    <a href="indexpage.php" class="back-button">← BACK</a>

    <h1>VIRTUAL MEMORY</h1>

    <?php if ($allocation_result): ?>
        <div class="result"><?= $allocation_result ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Enter File Size (KB):</label>
        <input type="text" name="file_size" required pattern="\d+" inputmode="numeric" placeholder="e.g. 512">
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
=======
<?php
$partitions = [223, 300, 689, 1034, 4302];
$allocation_result = "";
$allocated_files = [];
if (isset($_POST['history'])) {
    $allocated_files = json_decode($_POST['history'], true);
}
if (isset($_POST['reset_memory'])) {
    $allocated_files = [];
    $allocation_result = "Memory cleared.";
} 
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['file_size'])) {
    $file_size = (int)$_POST["file_size"];
    $allocated = false;

    foreach ($partitions as $partition) {
        $is_occupied = false;
        foreach ($allocated_files as $saved) {
            if ($saved['partition'] == $partition) {
                $is_occupied = true;
                break;
            }
        }
        if (!$is_occupied && $file_size <= $partition) {
            $allocated_files[] = ["size" => $file_size, "partition" => $partition];
            $allocation_result = "File of {$file_size}KB placed in Partition {$partition}KB";
            $allocated = true;
            break;
        }
    }

    if (!$allocated) {
        $allocation_result = "Not enough space or partition already occupied.";
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
        @import url('https://fonts.googleapis.com/css2?family=Archivo+Black&family=Inter:wght@400;600;700&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            text-align: center;
            padding: 100px 20px 60px; 
            background-color: #0d0628;
            background: radial-gradient(circle at 50% 0%, #3b117a 0%, #150833 45%, #0d0628 100%);
            color: #ffffff;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        body::before {
            content: ''; position: fixed; top: 10%; left: 50%; width: 100%; height: 100%;
            background: radial-gradient(circle, rgba(0, 242, 255, 0.08) 0%, rgba(255, 0, 212, 0.05) 40%, transparent 70%);
            transform: translateX(-50%); z-index: -1; pointer-events: none;
        }

        .back-button { 
            position: fixed; bottom: 30px; left: 30px; padding: 12px 25px; 
            font-weight: 700; background: rgba(255,255,255,0.08); color: white; 
            border-radius: 100px; text-decoration: none; backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1); transition: 0.3s; z-index: 1100;
        }
        .back-button:hover { background: #00f2ff; color: #000; transform: translateY(-3px); }

        h1 { 
            font-family: 'Archivo Black', sans-serif; font-size: clamp(2rem, 6vw, 3.5rem); 
            margin-bottom: 40px; text-transform: uppercase; letter-spacing: -1px; 
        }

        form { 
            margin-bottom: 40px; background: rgba(255, 255, 255, 0.03); 
            padding: 40px; border-radius: 30px; border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px); box-shadow: 0 20px 50px rgba(0,0,0,0.4);
            width: 100%; max-width: 500px;
        }

        label { font-weight: 700; color: #00f2ff; display: block; margin-bottom: 15px; }

        input[type="text"] { 
            padding: 16px; margin: 10px 0; width: 100%; font-size: 1rem; 
            border: 1px solid rgba(255,255,255,0.1); border-radius: 15px; 
            background: rgba(0,0,0,0.3); color: white; font-weight: 600; outline: none; 
            text-align: center;
        }
        
        .button-group { display: flex; gap: 10px; margin-top: 25px; }

        button { 
            background: #00f2ff; color: #000; border: none; cursor: pointer; 
            font-weight: 800; text-transform: uppercase; 
            padding: 16px; border-radius: 15px; width: 100%;
            box-shadow: 0 5px 20px rgba(0, 242, 255, 0.3); transition: 0.3s;
        }
        button:hover { transform: translateY(-3px); filter: brightness(1.2); }

        button.reset-btn { background: #ff4d4d; color: #fff; box-shadow: 0 5px 20px rgba(255, 77, 77, 0.3); }

        .result { 
            font-size: 1rem; margin-bottom: 25px; padding: 15px 30px; 
            background: rgba(0, 242, 255, 0.1); border-radius: 100px; 
            color: #00f2ff; font-weight: 700; border: 1px solid rgba(0, 242, 255, 0.3);
        }

        .container { display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; width: 100%; max-width: 1000px; }

        .box { 
            flex: 1; min-width: 280px; background: rgba(255, 255, 255, 0.03); 
            padding: 30px; border-radius: 30px; border: 1px solid rgba(255, 255, 255, 0.08); 
        }

        .box h2 { 
            font-family: 'Archivo Black', sans-serif; font-size: 1.3rem; 
            margin-bottom: 20px; color: #00f2ff; text-transform: uppercase; 
        }

        .partition { 
            padding: 15px; margin: 10px 0; border-radius: 12px; 
            background: rgba(255, 255, 255, 0.05); color: rgba(255,255,255,0.7); 
            font-weight: 700; border: 1px solid rgba(255,255,255,0.05);
        }

        .filled { 
            background: linear-gradient(135deg, #00f2ff, #a855f7); 
            color: #000; box-shadow: 0 5px 15px rgba(0, 242, 255, 0.3); 
            border: none;
        }
    </style>
</head>
<body>

    <a href="indexpage.php" class="back-button">← BACK</a>

    <h1>VIRTUAL MEMORY</h1>

    <?php if ($allocation_result): ?>
        <div class="result"><?= $allocation_result ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Enter File Size (KB):</label>
        <input type="text" name="file_size" required pattern="\d+" inputmode="numeric" placeholder="e.g. 512">
        
        <input type="hidden" name="history" value='<?= json_encode($allocated_files) ?>'>

        <div class="button-group">
            <button type="submit">Place File</button>
            <button type="submit" name="reset_memory" class="reset-btn" formnovalidate>Reset</button>
        </div>
    </form>

    <div class="container">
        <div class="box">
            <h2>Process Status</h2>
            <?php if (!empty($allocated_files)): ?>
                <?php foreach ($allocated_files as $file): ?>
                    <div class="partition filled">
                        File: <?= $file["size"] ?>KB (Partition <?= $file["partition"] ?>KB)
                    </div>
                <?php endforeach; ?>
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
                    foreach ($allocated_files as $saved) {
                        if ($saved["partition"] == $partition) {
                            $filled = true;
                            $display = "{$saved['size']}KB / {$partition}KB";
                            break;
                        }
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
>>>>>>> e1b76b42b08e65e81ce00b36d90e2b71f074de8d:CS222-2026-REDIMANO/virtual-memory.php
