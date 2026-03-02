<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CPU Scheduling - Preemptive</title>
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
        }
        body::before {
            content: ''; position: fixed; top: 10%; left: 50%; width: 100%; height: 100%;
            background: radial-gradient(circle, rgba(0, 242, 255, 0.08) 0%, rgba(255, 0, 212, 0.05) 40%, transparent 70%);
            transform: translateX(-50%); z-index: -1; pointer-events: none;
        }

        .top-menu {
            position: fixed; top: 25px; left: 50%; transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(15px);
            padding: 8px 15px; border-radius: 50px; display: flex; gap: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); z-index: 1000;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .top-menu a {
            text-decoration: none; color: rgba(255,255,255,0.6); font-weight: 700;
            padding: 8px 20px; border-radius: 40px; transition: 0.3s; font-size: 0.85rem;
        }

        .top-menu a.active { background: #00f2ff; color: #000; box-shadow: 0 0 15px rgba(0, 242, 255, 0.6); }

        .back-button { 
            position: fixed; bottom: 30px; left: 30px; padding: 12px 25px; 
            font-weight: 700; background: rgba(255,255,255,0.08); color: white; 
            border-radius: 100px; text-decoration: none; backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1); transition: 0.3s; z-index: 1100;
        }
        .back-button:hover { background: #00f2ff; color: #000; }

        h2 { font-family: 'Archivo Black', sans-serif; font-size: clamp(2rem, 6vw, 3.5rem); margin-bottom: 40px; text-transform: uppercase; letter-spacing: -1px; }
        h3 { font-family: 'Archivo Black', sans-serif; margin: 50px 0 20px; color: #00f2ff; font-size: 1.4rem; text-transform: uppercase; }

        .form-section { 
            margin: 20px auto; max-width: 850px; background: rgba(255, 255, 255, 0.03); 
            padding: 40px; border-radius: 30px; border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px); box-shadow: 0 20px 50px rgba(0,0,0,0.4);
        }

        button, select, input { 
            padding: 16px; margin: 10px 0; width: 100%; font-size: 0.95rem; 
            border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; 
            background: rgba(0,0,0,0.3); color: white; font-weight: 600; outline: none; 
        }
        
        button { 
            background: #00f2ff; color: #000; border: none; cursor: pointer; 
            font-weight: 800; text-transform: uppercase; margin-top: 25px; 
            box-shadow: 0 5px 20px rgba(0, 242, 255, 0.3); transition: 0.3s;
        }
        button:hover { transform: translateY(-3px); filter: brightness(1.2); }

        .table-wrapper { overflow-x: auto; width: 100%; border-radius: 20px; margin-top: 20px; border: 1px solid rgba(255,255,255,0.05); background: rgba(0,0,0,0.2); }
        table { width: 100%; border-collapse: collapse; color: #fff; }
        th { padding: 18px; color: #00f2ff; font-size: 0.75rem; text-transform: uppercase; border-bottom: 1px solid rgba(255,255,255,0.1); }
        td { padding: 18px; border-bottom: 1px solid rgba(255,255,255,0.03); }

        .gantt-container { 
            background: rgba(0,0,0,0.3); padding: 40px; border-radius: 25px; 
            border: 1px solid rgba(255,255,255,0.05); overflow-x: auto; 
        }
        .gantt-block { 
            height: 55px; margin-right: 4px; display: flex; align-items: center; 
            justify-content: center; color: #000; font-weight: 800; border-radius: 8px; 
            background: linear-gradient(135deg, #00f2ff, #a855f7); 
        }
        .gantt-time { font-weight: 700; color: #00f2ff; font-size: 0.85rem; margin-top: 10px; }

        .avg-container { 
            background: rgba(0, 242, 255, 0.1); padding: 25px 50px; border-radius: 100px; 
            display: inline-block; margin-top: 40px; border: 1px solid rgba(0, 242, 255, 0.2); 
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
                    <div style="padding:20px; border-top:4px solid #00f2ff; background:rgba(255,255,255,0.02); border-radius:15px; text-align:left; margin-top:15px;">
                        <strong style="color: #00f2ff;">Process P${i}:</strong><br><br>
                        Arrival Time:<input type="number" name="arrival_time[]" placeholder="AT" min="0" required>
                        Burst Time:<input type="number" name="burst_time[]" placeholder="BT" min="1" required>
                        ${priorityField}
                    </div>
                `;
            }
        }
    </script>
</head>
<body>

<div class="top-menu">
    <a href="#" class="active">Preemptive</a>
    <a href="non-preemptive.php">Non-Preemptive</a>
</div>

<a href="indexpage.php" class="back-button">← BACK</a>

<h2>CPU SCHEDULING</h2>

<form method="POST">
    <div class="form-section">
        <label><strong>Select Algorithm:</strong></label><br>
        <select name="algorithm" id="algorithm" onchange="showFields()" required>
            <option value="SJF">SRTF (Preemptive)</option>
            <option value="PRIORITY">Priority (Preemptive)</option>
            <option value="Round Robin">Round Robin</option>
        </select><br>

        <label><strong>Number of Processes:</strong></label><br>
        <input type="number" name="num_process" id="num_process" min="1" max="20" placeholder="Enter number of processes" onchange="showFields()" required><br>

        <div id="quantum_field"></div>
        <div id="process_container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;"></div>
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
        echo "<div class='gantt-block' style='width:{$w}px; min-width:{$w}px;'>{$b['id']}</div>";
    }
    echo "</div><div style='display:flex;margin-top:8px;'>";
    foreach($merged as $b){
        $w=$b['duration']*40;
        echo "<div class='gantt-time' style='width:{$w}px; min-width:{$w}px; text-align:left;'>{$b['time']}</div>";
    }
    $last_val = end($merged);
    echo "<div class='gantt-time'>".($last_val['time'] + $last_val['duration'])."</div></div></div></div>";

    echo "<div class='avg-container'><h4>Avg TAT: <span style='color:#00f2ff'>".number_format($total_tat/$num,2)."</span> | Avg WT: <span style='color:#00f2ff'>".number_format($total_wt/$num,2)."</span></h4></div>";
}
?>
</body>
</html>

