<!DOCTYPE html>
<!--try lang-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Management</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Archivo+Black&family=Inter:wght@400;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #230b4d;
            background: linear-gradient(135deg, #4b147d 0%, #230b4d 50%, #1a3a6d 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            width: 80vw;
            height: 80vh;
            background: radial-gradient(circle at center, rgba(255, 120, 230, 0.4) 0%, rgba(100, 220, 255, 0.3) 40%, transparent 70%);
            filter: blur(100px);
            z-index: 0;
        }

        .content {
            text-align: center;
            z-index: 10;
            width: 100%;
            max-width: 800px;
            padding: 30px;
        }

        .logo {
            width: 70px; 
            margin-bottom: 15px;
            filter: drop-shadow(0 0 10px rgba(255,255,255,0.4));
        }

        h1 {
            font-family: 'Archivo Black', sans-serif;
            font-size: clamp(2.5rem, 7vw, 5rem); 
            text-transform: uppercase;
            line-height: 0.9;
            letter-spacing: -2px;
            margin-bottom: 40px;
            color: #ffffff;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
        }

        h1 span {
            color: #00f2ff;
            text-shadow: 0 0 30px rgba(0, 242, 255, 0.6);
        }

        .btn-group {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        button {
            /* Sleek and Light Glassmorphism */
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            padding: 25px 25px; 
            font-size: 1rem;
            font-weight: 700;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
            min-width: 250px;
            max-width: 320px;
            text-align: left;
            position: relative;
        }

        button::after {
            content: '↗';
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 0.8rem;
            color: #00f2ff;
            background: rgba(0,0,0,0.2);
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        button:hover {
            background: rgba(255, 255, 255, 0.18);
            border-color: #00f2ff;
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 242, 255, 0.2);
        }

        @media (max-width: 600px) {
            h1 { font-size: 2.5rem; }
            button { width: 100%; max-width: 100%; }
        }
    </style>
</head>
<body>

    <div class="content">
        <img src="aulogo.png" alt="Logo" class="logo">
        
        <h1>MEMORY<br><span>MANAGEMENT</span></h1>

        <div class="btn-group">
            <button onclick="window.location.href='virtual-memory.php'">
                Memory Allocation
            </button>
            <button onclick="window.location.href='preemptive.php'">
                Scheduling Algorithm
            </button>
        </div>
    </div>

</body>
</html>

