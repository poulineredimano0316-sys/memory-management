<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CPU Scheduling - Non-Preemptive</title>
    <style>
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
            container.innerHTML = "";

            if (num > 20) { alert("Maximum 20 processes only."); document.getElementById("num_process").value = 20; return; }

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
    <a href="preemptive.php">Preemptive</a>
    <a href="#" class="active">Non-Preemptive</a>
</div>

<a href="indexpage.php" class="back-button">← BACK</a>

<h2>CPU SCHEDULING</h2>
<form method="POST">
    <div class="form-section">
        <label><strong>Select Scheduling Algorithm:</strong></label><br>
        <select name="algorithm" id="algorithm" onchange="showFields()" required>
            <option value="FCFS">FCFS</option>
            <option value="SJF">SJF (Non-Preemptive)</option>
            <option value="PRIORITY">Priority (Non-Preemptive)</option>
        </select><br>

        <label><strong>Number of Processes:</strong></label><br>
        <input type="number" name="num_process" id="num_process" min="1" max="20" placeholder="Enter number of processes" onchange="showFields()" required><br>

        <div id="process_container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;"></div>
        <button type="submit">Run Scheduling</button>
    </div>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['arrival_time'])) {
    $arrival = $_POST['arrival_time'];
    $burst = $_POST['burst_time'];
    $algorithm = $_POST['algorithm'];
    $num = count($arrival);
    $processes = [];

    for ($i = 0; $i < $num; $i++) {
        $p = [
            'id' => 'P'.($i+1),
            'arrival' => (int)$arrival[$i],
            'burst' => (int)$burst[$i],
            'completion' => null,
            'tat' => null,
            'wt' => null
        ];
        if ($algorithm === "PRIORITY") {
            $p['priority'] = (int)$_POST['priority'][$i];
        }
        $processes[] = $p;
    }
    usort($processes, fn($a,$b)=> $a['arrival'] - $b['arrival']);

    $current_time = 0;
    $completed = 0;
    $gantt = [];

    while ($completed < $num) {
        $idx = -1;

        if ($algorithm == "FCFS") {
            for ($i=0;$i<$num;$i++) { if ($processes[$i]['arrival'] <= $current_time && $processes[$i]['completion']===null) { $idx=$i; break; } }
        } elseif ($algorithm == "SJF") {
            $min_bt = PHP_INT_MAX;
            for ($i=0;$i<$num;$i++) { if ($processes[$i]['arrival'] <= $current_time && $processes[$i]['completion']===null && $processes[$i]['burst']<$min_bt) { $min_bt=$processes[$i]['burst']; $idx=$i; } }
        } elseif ($algorithm == "PRIORITY") {
            $high_prio = PHP_INT_MAX;
            for ($i=0;$i<$num;$i++) { if ($processes[$i]['arrival'] <= $current_time && $processes[$i]['completion']===null && $processes[$i]['priority']<$high_prio) { $high_prio=$processes[$i]['priority']; $idx=$i; } }
        }

        if ($idx != -1) {
            $p = &$processes[$idx];
            if ($current_time < $p['arrival']) { $current_time = $p['arrival']; }
            $p['completion']=$current_time+$p['burst'];
            $p['tat']=$p['completion']-$p['arrival'];
            $p['wt']=$p['tat']-$p['burst'];
            $gantt[]=['id'=>$p['id'],'duration'=>$p['burst']];
            $current_time=$p['completion']; $completed++; unset($p);
        } else {
            $current_time++;
        }
    }

    echo "<h3>Process Table</h3><div class='table-wrapper'><table><tr><th>Process</th><th>Arrival</th><th>Burst</th>";
    if ($algorithm==="PRIORITY") echo "<th>Priority</th>";
    echo "<th>Completion</th><th>Turnaround</th><th>Waiting</th></tr>";

    $total_tat=0; $total_wt=0;
    foreach ($processes as $p) {
        echo "<tr><td>{$p['id']}</td><td>{$p['arrival']}</td><td>{$p['burst']}</td>";
        if ($algorithm==="PRIORITY") echo "<td>{$p['priority']}</td>";
        echo "<td>{$p['completion']}</td><td>{$p['tat']}</td><td>{$p['wt']}</td></tr>";
        $total_tat+=$p['tat']; $total_wt+=$p['wt'];
    }
    echo "</table></div>";

    $merged=[]; 
    if(!empty($gantt)){ 
        $last=$gantt[0]['id']; 
        $dur=$gantt[0]['duration'];
        for($i=1;$i<count($gantt);$i++){ 
            if($gantt[$i]['id']==$last) $dur+=$gantt[$i]['duration']; 
            else { $merged[]=['id'=>$last,'duration'=>$dur]; $last=$gantt[$i]['id']; $dur=$gantt[$i]['duration']; } 
        }
        $merged[]=['id'=>$last,'duration'=>$dur]; 
    }

    echo "<h3>Gantt Chart</h3><div class='gantt-container'><div style='display:flex; flex-direction:column; align-items:flex-start;'>";
    echo "<div style='display:flex;'>";
    foreach($merged as $b){
        $w=$b['duration']*40;
        echo "<div class='gantt-block' style='width:{$w}px; min-width:{$w}px;'>{$b['id']}</div>";
    }
    echo "</div><div style='display:flex;margin-top:8px;'>";
    $time=0; 
    foreach($merged as $b){ 
        $w=$b['duration']*40; 
        echo "<div class='gantt-time' style='width:{$w}px; min-width:{$w}px; text-align:left;'>{$time}</div>"; 
        $time+=$b['duration']; 
    }
    echo "<div class='gantt-time'>{$time}</div></div></div></div>";

    echo "<div class='avg-container'>";
    echo "<h4>Avg TAT: <span style='color:#00f2ff'>".number_format($total_tat/$num,2)."</span> | Avg WT: <span style='color:#00f2ff'>".number_format($total_wt/$num,2)."</span></h4>";
    echo "</div>";
}
?>

</body>
</html>
