<?php
function Ribuan($angka)
{

    $hasil_rupiah = number_format($angka, 0, ',', '.');
    return $hasil_rupiah;
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Print PDF</title>
    <style>
        .container {
            padding-left: 10px;
        }

        table {
            border: 1px solid #424242;
            border-collapse: collapse;
            padding: 0;
        }

        th {
            background-color: #f2f2f2;
            color: black;
            padding: 15px;
        }

        tr,
        td {
            border-bottom: 1px solid #ddd;
            padding: 15px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <!-- <img src="<?= base_url() . '/' . $logo; ?>" width="80" height="80" alt="Logo" style="float:left;margin-top: 10px;margin-right: 10px;"> -->
        <h1><?= $toko['nama_toko']; ?></h1>
        <?= $toko['alamat_toko']; ?> - Telp/WA: <?= $toko['telp']; ?> - Email: <?= $toko['email']; ?> - NIB: <?= $toko['NIB']; ?>
        <hr />
        <h1 align="center">Laporan <?= mediumdate_indo($tgl_start); ?> &mdash; <?= mediumdate_indo($tgl_end); ?></h1>
        <h4>Penjualan</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">No.Faktur</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Subtotal</th>
                    <th scope="col">Diskon</th>
                    <th scope="col">Jumlah*</th>
                    <th scope="col">Total Laba</th>
                    <th scope="col">Piutang</th>
                    <th scope="col">User</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($data as $row) : ?>
                    <tr>
                        <td><?= $no++; ?> <?= $row['faktur']; ?></td>
                        <td><?= $row['jumlah']; ?></td>
                        <td><?= Ribuan($row['subtotal']); ?></td>
                        <td><?= Ribuan($row['diskon']); ?></td>
                        <td><?= Ribuan($row['total']); ?></td>
                        <td><?= Ribuan($row['total_laba']); ?></td>
                        <td style="font-size: 12px;">
                            <?php if ($row['id_piutang'] == null) { ?>
                                -
                            <?php } else { ?>
                                Status: <?php if ($row['status_piutang'] == 1) { ?>Lunas<?php } else { ?>Belum Lunas<?php } ?><br />
                                Jml.Bayar: <?= Ribuan($row['bayar']); ?><br />
                                Sisa Piutang: <?= Ribuan($row['sisa_piutang']); ?><br />
                                Keterangan: <?= $row['keterangan'] ?? "-"; ?>
                            <?php } ?>
                        </td>
                        <td><?= $row['nama']; ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php
                $jumlah = 0;
                $subtotal = 0;
                $total = 0;
                $totalLaba = 0;
                $sisaPiutang = 0;

                foreach ($data as $row) {
                    $jumlah += $row['jumlah'];
                    $subtotal += $row['subtotal'];
                    $total += $row['total'];
                    $totalLaba += $row['total_laba'];
                    $sisaPiutang += $row['sisa_piutang'];
                }
                ?>
                <tr>
                    <td colspan="1"></td>
                    <td align="right">Total</td>
                    <td><?= $jumlah; ?></td>
                    <td><?= Ribuan($subtotal); ?></td>
                    <td></td>
                    <td><?= Ribuan($total); ?></td>
                    <td><?= Ribuan($totalLaba); ?></td>
                    <td><?= Ribuan($sisaPiutang); ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="1"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td align="right">=</td>
                    <td><?= Ribuan($total-$sisaPiutang); ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

</body>

</html>