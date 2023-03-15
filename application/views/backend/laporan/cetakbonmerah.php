<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cetak</title>
</head>

<body>
    <div style="width:auto; margin: auto;">
        <center>
            <h3>Laporan Data Bon Merah </h3>
            <table border="1" width="100%" style="border-collapse:collapse;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Biaya</th>
                        <th>Keterangan / Bagian</th>
                        <th>Karyawan / Atasan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($bon as $b) : ?>
                        <tr>
                            <td><?= $i; ?></td>
                            <td><?= format_indo(date($b['tgl_pettycash'])); ?></td>
                            <td align="right" ><?= number_format($b['biaya_pettycash']); ?></td>
                            <td align="center"><?= $b['ket_pettycash'].''.' ('.$b['namabagian'].')'; ?></td>
                            <td align="center"><?= $b['user_name']. ' ('.$b['namaatasan'].')' ; ?></td>
                            <td align="center"><?= $b['status']; ?></td>
                        </tr>
                        <?php $i++; ?>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </center>
    </div>
</body>

</html>