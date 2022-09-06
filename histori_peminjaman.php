<?php
include "header.php";
include "config.php";
?>
<h2>Histori Peminjaman Buku</h2>
<table class="table table-hover table-striped">
    <thead>
        <th>NO</th>
        <th>Tanggal Pinjam</th>
        <th>Tanggal harus Kembali</th>
        <th>Nama Buku</th>
        <th>Status</th>
        <th>Aksi</th>
        <th>Hapus</th>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM peminjaman_buku ORDER BY id_peminjaman_buku DESC";
        $qry_histori = mysqli_query($connection, $sql);
        $no = 0;

        while ($dt_histori = mysqli_fetch_array($qry_histori)) {
            $no++;
            //menampilkan buku yang dipinjam
            $buku_dipinjam = "<ol>";
            $qry_buku = mysqli_query($connection, "select * from  detail_peminjaman_buku join buku on buku.id_buku=detail_peminjaman_buku.id_buku where id_peminjaman_buku = '" . $dt_histori['id_peminjaman_buku'] . "'");
            while ($dt_buku = mysqli_fetch_array($qry_buku)) {
                $buku_dipinjam .= "<li>" . $dt_buku['nama_buku'] . "</li>";
            }
            $buku_dipinjam .= "</ol>";
            //menampilkan status sudah kembali atau belum
            $qry_cek_kembali = mysqli_query($connection, "select * from pengembalian_buku where id_peminjaman_buku = '" . $dt_histori['id_peminjaman_buku'] . "'");
            if (mysqli_num_rows($qry_cek_kembali) > 0) {
                $dt_kembali = mysqli_fetch_array($qry_cek_kembali);
                $denda = "denda Rp. " . $dt_kembali['denda'];
                $status_kembali = "<label class='alert alert-success'>Sudah kembali <br>" . $denda . "</label>";
                $button_kembali = "";
            } else {
                $status_kembali = "<label class='alert alert-danger'>Belum kembali</label>";
                $button_kembali = "<a href='kembali.php?id=" . $dt_histori['id_peminjaman_buku'] . "' class='btn btn-warning' onclick='return confirm(\"Buku berhasil dikembalikan\")'>Kembalikan</a>";
            }
            $button_hapus = "<a href='hapus_histori.php?id_peminjaman_buku=" . $dt_histori['id_peminjaman_buku'] . "' class='btn btn-danger' onclick='return confirm(\"Apakah anda yakin menghapus data ini?\")'>Hapus</a>";
        ?>
            <tr>
                <td><?= $no ?></td>
                <td><?= $dt_histori['tanggal_pinjam'] ?></td>
                <td><?= $dt_histori['tanggal_kembali'] ?></td>
                <td><?= $buku_dipinjam ?></td>
                <td><?= $status_kembali ?></td>
                <td><?= $button_kembali ?></td>
                <td><?= $button_hapus ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<?php
include "footer.php";
?>