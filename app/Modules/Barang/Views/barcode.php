<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Barcode</title>
    <style>
        .barcode-text {
            letter-spacing: 7px;
            margin-top: 3px;
            display: block;
        }
    </style>
</head>

<body onLoad="javascript:window.print();">
    <div class="barcode-container" style="text-align: center;width:100% !important;">
        <?php if ($jumlah == 1) { ?>
            <?php if (in_array($tipe, ["JPG", "PNG"])) {
                echo '<img src="data:image/png;base64,' . base64_encode($barcode) . '" />';
            } else {
                echo $barcode;
            }
            ?>
            <span class="barcode-text"><?php echo $text ?></span>
        <?php } else { ?>
            <?php
            for ($i = 1; $i <= $jumlah; $i++) { ?>
                <div style="margin-bottom: 40px;">
                    <?php if (in_array($tipe, ["JPG", "PNG"])) {
                        echo '<img src="data:image/png;base64,' . base64_encode($barcode) . '" />';
                    } else {
                        echo $barcode;
                    }
                    ?>
                    <span class="barcode-text"><?php echo $text ?></span>
                </div>
            <?php }
            ?>
        <?php } ?>
    </div>
</body>

</html>