<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CPU Scheduling - Preemptive</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Archivo+Black&family=Inter:wght@400;700&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            text-align: center;
            padding: 80px 20px 40px; 
            background: linear-gradient(135deg, #e0e7ff 0%, #f3e8ff 40%, #d1fae5 70%, #fce7f3 100%);
            color: #312e81;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }
        .top-menu {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            padding: 10px 20px;
            border-radius: 50px;
            display: flex;
            gap: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            z-index: 1000;
            border: 1px solid rgba(255,255,255,0.5);
            white-space: nowrap;
        }

        .top-menu a {
            text-decoration: none;
            color: #312e81;
            font-weight: 700;
            padding: 8px 15px;
            border-radius: 20px;
            transition: 0.3s;
            font-size: 0.9rem;
        }

        .top-menu a.active { background: #312e81; color: white; }
        .top-menu a:hover:not(.active) { background: #e0e7ff; }

        .back-button { 
            position: fixed; 
            bottom: 25px;
            left: 25px; 
            padding: 12px 25px; 
            font-weight: 700; 
            background: white; 
            color: #312e81; 
            border-radius: 100px; 
            text-decoration: none; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); 
            transition: 0.3s; 
            z-index: 1100; 
            border: 1px solid #e2e8f0; 
        }
        .back-button:hover { background: #312e81; color: white; transform: translateY(-3px); }

        .blob { position: fixed; z-index: -1; border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; filter: blur(8px); animation: morph 10s ease-in-out infinite alternate; }
        .blob1 { width: clamp(250px, 40vw, 400px); height: clamp(250px, 40vw, 400px); top: -100px; right: -50px; background: linear-gradient(135deg, #a5b4fc, #fbcfe8); }
        .blob2 { width: clamp(200px, 35vw, 350px); height: clamp(200px, 35vw, 350px); bottom: -80px; left: -50px; background: linear-gradient(135deg, #6ee7b7, #93c5fd); animation-delay: -2s; }

        @keyframes morph {
            0% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            50% { border-radius: 50% 50% 30% 70% / 50% 30% 70% 50%; }
            100% { border-radius: 70% 30% 50% 50% / 30% 70% 30% 70%; }
        }

        h2 { font-family: 'Archivo Black', sans-serif; font-size: clamp(1.8rem, 5vw, 3rem); letter-spacing: -2px; margin: 40px 0 30px; background: linear-gradient(to bottom, #312e81, #6366f1); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        h3 { font-family: 'Archivo Black', sans-serif; margin: 30px 0 15px; color: #4338ca; font-size: 1.5rem; text-transform: uppercase; }

        .form-section { margin: 20px auto; max-width: 900px; background: #ffffff; padding: 40px; border-radius: 35px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05); z-index: 10; position: relative; width: 100%; }
        
        #process_container { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); 
            gap: 15px; 
            margin-top: 20px; 
        }

        button, select, input { padding: 16px; margin: 10px 0; width: 100%; font-size: 1rem; border: 2px solid #f1f5f9; border-radius: 15px; background-color: white; color: #312e81; font-weight: 600; outline: none; transition: 0.3s; }
        input::placeholder { color: #94a3b8; }
        
        button { background-color: #312e81; color: white; border: none; cursor: pointer; font-weight: 700; text-transform: uppercase; margin-top: 25px; letter-spacing: 1px; }
        button:hover { background-color: #4338ca; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(49,46,129,0.2); }

        .table-wrapper { overflow-x: auto; width: 100%; border-radius: 20px; margin-top: 20px; }
        table { border-collapse: separate; border-spacing: 0 10px; width: 100%; min-width: 600px; color: #312e81; }
        th { padding: 15px; font-family: 'Archivo Black', sans-serif; font-size: 0.8rem; text-transform: uppercase; color: #64748b; }
        td { background-color: #ffffff; padding: 18px; font-weight: 600; border-bottom: 1px solid #f1f5f9; }
        td:first-child { border-radius: 12px 0 0 12px; color: #6366f1; }
        td:last-child { border-radius: 0 12px 12px 0; }

        .gantt-container { background: #ffffff; padding: 40px; border-radius: 30px; overflow-x: auto; width: 100%; margin: 20px auto; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
        .gantt-block { height: 50px; margin-right: 4px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; border-radius: 10px; }
        .gantt-time { font-weight: 700; color: #6366f1; font-size: 0.9rem; margin-top: 10px; }

        .avg-container { background: white; padding: 25px 45px; border-radius: 100px; display: inline-block; margin-top: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; }

        @media (max-width: 768px) {
            h2 { font-size: 2rem; }
            .top-menu { top: 10px; width: 95%; padding: 5px 10px; }
            .back-button { width: calc(100% - 40px); left: 20px; text-align: center; justify-content: center; }
            #process_container { grid-template-columns: 1fr; }
            body { padding-top: 100px; }
        }
    </style>
    
    <script>
        function showFields() {
            const num = document.getElementById("num_process").value;
            const algo = document.getElementById("algorithm").value;
            const container = document.getElementById("process_container");
            const quantumField = document.getElementById("quantum_field");
            container.innerHTML = "";

            if (num > 20) { alert("Maximum 20 processes only."); document.getElementById("num_process").value = 20; return; }

            if (algo === "Round Robin") {
                quantumField.innerHTML = `Time Quantum:<br><input type="number" name="time_quantum" min="1" required><br><br>`;
            } else { quantumField.innerHTML = ""; }

            for (let i = 1; i <= num; i++) {
                let priorityField = (algo === "PRIORITY") ? `Priority:<br><input type="number" name="priority[]" min="1" required>` : "";
                container.innerHTML += `
                    <div style="padding:20px; border-top:5px solid #6366f1; background:#f8fafc; border-radius:15px; text-align:left; box-shadow: 0 5px 15px rgba(0,0,0,0.02);">
                        <strong style="color: #312e81;">Process P${i}:</strong><br><br>
                        Arrival Time:<input type="number" name="arrival_time[]" placeholder="Enter number of AT" min="0" required>
                        Burst Time:<input type="number" name="burst_time[]" placeholder="Enter number of BT" min="1" required>
                        ${priorityField}
                    </div>
                `;
            }
        }
    </script>
</head>
<body>

<div class="blob blob1"></div>
<div class="blob blob2"></div>

<div class="top-menu">
    <a href="#" class="active">Preemptive</a>
    <a href="non-preemptive.php">Non-Preemptive</a>
</div>

<a href="indexpage.php" class="back-button">← Back</a>

<h2>CPU Scheduling - Preemptive</h2>


<form method="POST">
    <div class="form-section">
        <label><strong>Select Scheduling Algorithm:</strong></label><br>
        <select name="algorithm" id="algorithm" onchange="showFields()" required>
            <option value="SJF">SRTF (Preemptive)</option>
            <option value="PRIORITY">Priority (Preemptive)</option>
            <option value="Round Robin">Round Robin</option>
        </select><br>

        <label><strong>Number of Processes:</strong></label><br>
        <input type="number" name="num_process" id="num_process" min="1" max="20" placeholder="Enter number of processes" onchange="showFields()" required><br>

        <div id="quantum_field"></div>
        <div id="process_container"></div>
        <button type="submit">Run Scheduling</button>
    </div>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['arrival_time'])) {
    $algorithm = $_POST['algorithm'];
    $arrival = $_POST['arrival_time'];
    $burst = $_POST['burst_time'];
    $priority = $_POST['priority'] ?? [];
    $num = count($arrival);
    $time_quantum = $_POST['time_quantum'] ?? null;

    $processes = [];
    for ($i = 0; $i < $num; $i++) {
        $processes[] = [
            'id' => 'P'.($i+1),
            'arrival' => (int)$arrival[$i],
            'burst' => (int)$burst[$i],
            'remaining' => (int)$burst[$i],
            'priority' => (int)($priority[$i] ?? 0),
            'completion' => null, 'tat' => null, 'wt' => null
        ];
    }

    usort($processes, fn($a,$b)=> $a['arrival']-$b['arrival']);
    $current_time = 0; $completed = 0; $gantt = [];

    if ($algorithm == 'SJF' || $algorithm == 'PRIORITY') {
        while ($completed < $num) {
            $idx = -1; $min_val = PHP_INT_MAX;
            for ($i = 0; $i < $num; $i++) {
                if ($processes[$i]['arrival'] <= $current_time && $processes[$i]['remaining'] > 0) {
                    if ($algorithm == 'SJF') {
                        if ($processes[$i]['remaining'] < $min_val) { $min_val = $processes[$i]['remaining']; $idx = $i; }
                    } else {
                        if ($processes[$i]['priority'] < $min_val) { $min_val = $processes[$i]['priority']; $idx = $i; }
                    }
                }
            }
            if ($idx != -1) {
                $gantt[] = ['id' => $processes[$idx]['id'], 'start' => $current_time];
                $processes[$idx]['remaining']--;
                if ($processes[$idx]['remaining'] == 0) {
                    $processes[$idx]['completion'] = $current_time + 1;
                    $processes[$idx]['tat'] = $processes[$idx]['completion'] - $processes[$idx]['arrival'];
                    $processes[$idx]['wt'] = $processes[$idx]['tat'] - $processes[$idx]['burst'];
                    $completed++;
                }
                $current_time++;
            } else { $current_time++; }
        }
    } elseif ($algorithm == 'Round Robin') {
        $queue = []; $time = 0; $visited = array_fill(0,$num,false);
        while ($completed < $num) {
            for ($i=0;$i<$num;$i++) if ($processes[$i]['arrival'] <= $time && !$visited[$i]) { $queue[]=$i; $visited[$i]=true; }
            if(empty($queue)) { $time++; continue; }
            $idx = array_shift($queue);
            $start_block_time = $time;
            $run = min((int)$time_quantum, $processes[$idx]['remaining']);
            for ($i=0;$i<$run;$i++) {
                $time++; $processes[$idx]['remaining']--;
                for ($j=0;$j<$num;$j++) if($processes[$j]['arrival']<=$time && !$visited[$j]) { $queue[]=$j; $visited[$j]=true; }
            }
            $gantt[] = ['id' => $processes[$idx]['id'], 'start' => $start_block_time, 'end' => $time];
            if ($processes[$idx]['remaining'] > 0) $queue[]=$idx;
            else { $processes[$idx]['completion']=$time; $processes[$idx]['tat']=$time-$processes[$idx]['arrival']; $processes[$idx]['wt']=$processes[$idx]['tat']-$processes[$idx]['burst']; $completed++; }
        }
    }

    echo "<h3>Process Table</h3><div class='table-wrapper'><table><tr><th>Process</th><th>Arrival</th><th>Burst</th>";
    if($algorithm=='PRIORITY') echo "<th>Priority</th>";
    echo "<th>Completion</th><th>Turnaround</th><th>Waiting</th></tr>";
    $total_tat = 0; $total_wt = 0;
    foreach($processes as $p){
        echo "<tr><td>{$p['id']}</td><td>{$p['arrival']}</td><td>{$p['burst']}</td>";
        if($algorithm=='PRIORITY') echo "<td>{$p['priority']}</td>";
        echo "<td>{$p['completion']}</td><td>{$p['tat']}</td><td>{$p['wt']}</td></tr>";
        $total_tat += $p['tat']; $total_wt += $p['wt'];
    }
    echo "</table></div>";

    $merged = [];
    if ($algorithm != 'Round Robin' && !empty($gantt)) {
        $curr = $gantt[0]; $dur = 1;
        for ($i = 1; $i < count($gantt); $i++) {
            if ($gantt[$i]['id'] == $curr['id']) { $dur++; }
            else { $merged[] = ['id' => $curr['id'], 'duration' => $dur, 'time' => $curr['start']]; $curr = $gantt[$i]; $dur = 1; }
        }
        $merged[] = ['id' => $curr['id'], 'duration' => $dur, 'time' => $curr['start']];
    } else {
        foreach($gantt as $g) { $merged[] = ['id' => $g['id'], 'duration' => $g['end'] - $g['start'], 'time' => $g['start']]; }
    }

    echo "<h3>Gantt Chart</h3><div class='gantt-container'><div style='display:flex; flex-direction:column; align-items:flex-start;'>";
    echo "<div style='display:flex;'>";
    foreach($merged as $b){
        $w = $b['duration']*40;
        echo "<div class='gantt-block' style='width:{$w}px; min-width:{$w}px; background:linear-gradient(135deg,#6366f1,#a855f7);'>{$b['id']}</div>";
    }
    echo "</div><div style='display:flex;margin-top:8px;'>";
    foreach($merged as $b){
        $w=$b['duration']*40;
        echo "<div class='gantt-time' style='width:{$w}px; min-width:{$w}px; text-align:left;'>{$b['time']}</div>";
    }
    $last_val = end($merged);
    echo "<div class='gantt-time'>".($last_val['time'] + $last_val['duration'])."</div></div></div></div>";

    echo "<div class='avg-container'><h4 style='color:#312e81;'>Average TAT: <span style='color:#4338ca'>".number_format($total_tat/$num,2)."</span> | Average WT: <span style='color:#4338ca'>".number_format($total_wt/$num,2)."</span></h4></div>";
}
?>
</body>
</html>