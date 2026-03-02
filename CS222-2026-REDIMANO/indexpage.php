<!DOCTYPE html>
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
            background: linear-gradient(135deg, #e0e7ff 0%, #f3e8ff 40%, #d1fae5 70%, #fce7f3 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden; 
            position: relative;
            padding: 20px;
        }
        .blob {
            position: absolute;
            background: linear-gradient(135deg, rgba(165, 180, 252, 0.7), rgba(251, 207, 232, 0.7));
            backdrop-filter: blur(10px);
            z-index: -1;
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            box-shadow: inset 10px -10px 20px rgba(255, 255, 255, 0.4), 
                        20px 20px 40px rgba(0, 0, 0, 0.05);
            animation: morph 8s ease-in-out infinite alternate;
        }

        .blob1 {
            width: 30vw; 
            height: 30vw;
            min-width: 250px;
            min-height: 250px;
            top: -50px;
            right: -50px;
            background: linear-gradient(135deg, #a5b4fc, #fbcfe8);
        }

        .blob2 {
            width: 25vw;
            height: 25vw;
            min-width: 200px;
            min-height: 200px;
            bottom: -50px;
            left: -50px;
            background: linear-gradient(135deg, #6ee7b7, #93c5fd);
            animation-duration: 10s;
            border-radius: 58% 42% 41% 59% / 51% 54% 46% 49%;
        }

        @keyframes morph {
            0% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            50% { border-radius: 50% 50% 30% 70% / 50% 30% 70% 50%; }
            100% { border-radius: 70% 30% 50% 50% / 30% 70% 30% 70%; }
        }

        .content {
            text-align: center;
            z-index: 10;
            width: 100%;
            max-width: 800px;
        }

        .logo {
            width: clamp(80px, 15vw, 120px); 
            margin-bottom: 20px;
            filter: drop-shadow(0 5px 15px rgba(0,0,0,0.08));
        }

        h1 {
            font-family: 'Archivo Black', sans-serif;
            font-size: clamp(2.5rem, 8vw, 5.5rem); 
            text-transform: uppercase;
            line-height: 0.9;
            letter-spacing: -2px;
            margin-bottom: clamp(30px, 5vh, 50px);
            background: linear-gradient(to bottom, #312e81, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap; 
        }

        button {
            background: #ffffff;
            color: #312e81;
            border: 2px solid rgba(49, 46, 129, 0.1);
            padding: clamp(15px, 3vw, 22px) clamp(25px, 5vw, 40px);
            font-size: clamp(0.9rem, 2vw, 1.1rem);
            font-weight: 700;
            border-radius: 100px;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            flex: 0 1 auto; 
            width: auto;
        }

        button:hover {
            transform: scale(1.05) translateY(-5px);
            background: #312e81;
            color: #ffffff;
            box-shadow: 0 20px 40px rgba(49, 46, 129, 0.2);
        }

        .sparkle {
            position: absolute;
            font-size: clamp(1.5rem, 4vw, 2.5rem);
            color: #6366f1;
            opacity: 0.4;
            pointer-events: none;
        }

        @media (max-width: 600px) {
            h1 {
                letter-spacing: -1px;
            }
            .btn-group {
                flex-direction: column; 
                align-items: center;
            }
            button {
                width: 100%; 
                max-width: 300px;
            }
            .blob1 { top: -120px; right: -80px; }
            .blob2 { bottom: -100px; left: -80px; }
        }
    </style>
</head>
<body>

    <div class="blob blob1"></div>
    <div class="blob blob2"></div>
    
    <span class="sparkle" style="top: 10%; left: 8%;">✦</span>
    <span class="sparkle" style="bottom: 12%; right: 8%;">✦</span>

    <div class="content">
        <img src="aulogo.png" alt="Logo" class="logo">
        
        <h1>MEMORY<br>MANAGEMENT</h1>

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