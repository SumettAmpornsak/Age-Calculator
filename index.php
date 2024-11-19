<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Sumett Ampornsak">
    <title>คำนวณอายุ</title>
    <!-- ฟอนต์ Kanit -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link
      rel="icon"
      href="https://i.ibb.co/h8xZb1d/dob-4731797-1280.png"
      type="image/x-icon"
    />
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background: linear-gradient(135deg, #e0c3fc, #8ec5fc);
            color: #333;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }
        .container {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 500px;
            width: 100%;
        }
        h1 {
            font-weight: 600;
            font-size: 2rem;
            color: #5a5af7;
        }
        button {
            background: #5a5af7;
            border: none;
            color: #fff;
            font-weight: 600;
        }
        button:hover {
            background: #4a4ae3;
        }
        .alert {
            font-size: 1.2rem;
            font-weight: 500;
        }
        #confetti-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }
        footer {
            width: 100%;
            background-color: white;
            color: #000;
            text-align: center;
            padding: 5px 0;
            position: fixed;
            bottom: 0;
        }
        footer p {
            margin: 5px 0;
        }
        footer a {
            color: #007bff;
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline;
        }
        @media (max-width: 576px) {
            h1 {
                font-size: 1.5rem;
            }
            .container {
                padding: 20px;
            }
            button {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <canvas id="confetti-canvas"></canvas>
    <div class="container">
        <h1 class="text-center">คำนวณอายุ</h1>
        <form method="POST" class="mt-4">
            <div class="row">
                <div class="col-4">
                    <label for="day" class="form-label">วัน</label>
                    <select id="day" name="day" class="form-select" required>
                        <option value="" disabled selected>วัน</option>
                        <?php for ($i = 1; $i <= 31; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-4">
                    <label for="month" class="form-label">เดือน</label>
                    <select id="month" name="month" class="form-select" required>
                        <option value="" disabled selected>เดือน</option>
                        <?php
                        $months = [
                            "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", 
                            "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", 
                            "พฤศจิกายน", "ธันวาคม"
                        ];
                        foreach ($months as $index => $month) {
                            echo "<option value='" . ($index + 1) . "'>$month</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-4">
                    <label for="year" class="form-label">ปี พ.ศ.</label>
                    <select id="year" name="year" class="form-select" required>
                        <option value="" disabled selected>ปี</option>
                        <?php for ($i = 2400; $i <= (date("Y") + 543); $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-4">คำนวณอายุ</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $day = $_POST['day'];
            $month = $_POST['month'];
            $yearThai = $_POST['year'];
        
            // แปลงปี พ.ศ. เป็นปี ค.ศ.
            $yearAD = $yearThai - 543;
        
            // ตรวจสอบความถูกต้องของวันที่
            if (!checkdate($month, $day, $yearAD)) {
                echo "<div class='mt-4 alert alert-danger text-center'>วันที่ไม่ถูกต้อง กรุณากรอกใหม่</div>";
                exit;
            }
        
            // สร้างวันที่ในรูปแบบปี ค.ศ.
            $birthdate = "$yearAD-$month-$day";
        
            // คำนวณอายุ
            $birthDateTime = new DateTime($birthdate);
            $currentDateTime = new DateTime();
        
            $age = $currentDateTime->diff($birthDateTime);
        
            // แสดงผลคำตอบพร้อมวันเกิดที่กรอกมา
            echo "<div class='mt-4 alert alert-success text-center' id='result'>";
            echo "วันเกิดของคุณคือ: " . str_pad($day, 2, '0', STR_PAD_LEFT) . "/" . str_pad($month, 2, '0', STR_PAD_LEFT) . "/$yearThai<br>";
            echo "คุณมีอายุ " . $age->y . " ปี " . $age->m . " เดือน " . $age->d . " วัน";
            echo "</div>";
        }
        ?>
    </div>

    <!-- ใส่ JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <script>
        // ตรวจจับคำตอบและเริ่มเอฟเฟกต์พลุ
        window.onload = function() {
            const resultDiv = document.getElementById('result');
            if (resultDiv) {
                // เอฟเฟกต์พลุ
                const duration = 2 * 1000;
                const end = Date.now() + duration;

                (function frame() {
                    confetti({
                        particleCount: 3,
                        angle: 60,
                        spread: 55,
                        origin: { x: 0 }
                    });
                    confetti({
                        particleCount: 3,
                        angle: 120,
                        spread: 55,
                        origin: { x: 1 }
                    });

                    if (Date.now() < end) {
                        requestAnimationFrame(frame);
                    }
                })();
            }
        };
    </script>
</body>
<footer>
    <p>&copy; Sumett Ampornsak 2024</p>
    <p><a href="mailto:Sumett13ampornsak@gmail.com">Sumett13ampornsak@gmail.com</a></p>
</footer>
</html>
