<?php

require 'config.php';

function tambah_siswa($data)
{
    global $connection;

    if ($data) {
        $nama_siswa = $data['nama_siswa'];
        $tanggal_lahir = $data['tanggal_lahir'];
        $alamat = $data['alamat'];
        $gender = $data['gender'];
        $username = $data['username'];
        $password = md5($data['password']);
        $id_kelas = $data['id_kelas'];
        if (empty($nama_siswa)) {
            echo "<script>alert('nama siswa tidak boleh kosong');location.href='tambah_siswa.php';</script>";
        } elseif (empty($username)) {
            echo "<script>alert('username tidak boleh kosong');location.href='tambah_siswa.php';</script>";
        } elseif (empty($password)) {
            echo "<script>alert('password tidak boleh kosong');location.href='tambah_siswa.php';</script>";
        } else {
            $sql = "INSERT INTO data_siswa(nama_siswa, tanggal_lahir, gender, alamat, username, password, id_kelas) VALUE ('$nama_siswa', '$tanggal_lahir', '$gender', '$alamat', '$username','$password', '$id_kelas')";
            $insert = mysqli_query($connection, $sql);
            if ($insert) {
                echo "<script>alert('Sukses menambahkan siswa');location.href='tambah_siswa.php';</script>";
            } else {
                echo "<script>alert('Gagal menambahkan siswa');location.href='tambah_siswa.php';</script>";
            }
        }
    }
    echo "<script>alert('Tidak ada data masuk')</script>";
}

function tambah_kelas($data)
{
    global $connection;

    if ($data) {
        $nama_kelas = $data['nama_kelas'];
        $kelompok = $data['kelompok'];
        $wali_kelas = $data['wali_kelas'];
        if (empty($nama_kelas)) {
            echo "<script>alert('nama kelas tidak boleh kosong');location.href='tambah_kelas.php';</script>";
        } elseif (empty($kelompok)) {
            echo "<script>alert('kelompok tidak boleh kosong');location.href='tambah_kelas.php';</script>";
        } else {
            $sql = "INSERT INTO kelas(nama_kelas, kelompok, wali_kelas) VALUE ('$nama_kelas', '$kelompok', '$wali_kelas')";
            $insert = mysqli_query($connection, $sql);
            if ($insert) {
                echo "<script>alert('Sukses menambahkan kelas');location.href=tambah_kelas.php;</script>";
            } else {
                echo "<script>alert('Gagal menambahkan kelas');location.href=tambah_kelas.php;</script>";
            }
        }
    }
}

function ubah_siswa($data)
{
    global $connection;

    if ($data) {
        $id_siswa = $_GET['id_siswa'];
        $nama_siswa = $data['nama_siswa'];
        $tanggal_lahir = $data['tanggal_lahir'];
        $alamat = $data['alamat'];
        $gender = $data['gender'];
        $username = $data['username'];
        $password = md5($data['password']);
        $id_kelas = $data['id_kelas'];

        if (empty($nama_siswa)) {
            echo "<script>alert('nama siswa tidak boleh kosong');location.href='tambah_siswa.php';</script>";
        } elseif (empty($username)) {
            echo "<script>alert('username tidak boleh kosong');location.href='tambah_siswa.php';</script>";
        } else {

            if (empty($password)) {
                $sql = "UPDATE `data_siswa` SET `nama_siswa`='$nama_siswa',`tanggal_lahir`='$tanggal_lahir',`gender`='$gender',`alamat`='$alamat',`username`='$username',`id_kelas`='$id_kelas' WHERE id_siswa = $id_siswa";

                $update = mysqli_query($connection, $sql);

                if ($update) {
                    echo "<script>alert('Sukses update siswa');location.href='tampil_siswa.php';</script>";
                } else {
                    echo "<script>alert('Gagal update siswa');location.href='ubah_siswa.php?id_siswa=" . $id_siswa . "';</script>";
                }
            } else {
                $sql = "UPDATE `data_siswa` SET `nama_siswa`='$nama_siswa',`tanggal_lahir`='$tanggal_lahir',`gender`='$gender',`alamat`='$alamat',`username`='$username',`password`='$password',`id_kelas`='$id_kelas' WHERE id_siswa = $id_siswa";

                $update = mysqli_query($connection, $sql);
                if ($update) {
                    echo "<script>alert('Sukses update siswa');location.href='tampil_siswa.php';</script>";
                } else {
                    echo "<script>alert('Gagal update siswa');location.href='ubah_siswa.php?id_siswa=" . $id_siswa . "';</script>";
                }
            }
        }
    }
}

function tambah_buku($data)
{

    global $connection;

    $nama_buku = $data['nama_buku'];
    $pengarang = $data['pengarang'];
    $deskripsi = $data['deskripsi'];

    // Proses upload foto
    $ekstensi =  array('png', 'jpg', 'jpeg', 'gif', 'JPG');
    $filename = $_FILES['foto']['name'];
    $ukuran = $_FILES['foto']['size'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    if (!in_array($ext, $ekstensi)) {
        header("location:tambah_buku.php?alert=gagal_ekstensi");
    } else {
        if ($ukuran < 1044070) {
            $xx = $filename;
            move_uploaded_file($_FILES['foto']['tmp_name'], 'assets/foto_produk/' . '' . $filename);
            mysqli_query($connection, "INSERT INTO buku VALUES(NULL,'$nama_buku','$pengarang','$deskripsi','$xx')");
            echo "<script>alert('Sukses menambahkan buku');location.href='tambah_buku.php';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan buku');location.href='tambah_buku.php';</script>";
        }
    }
}

function login($data)
{
    global $connection;

    if ($data) {
        $username = $data['username'];
        $password = md5($data['password']);
        if (empty($username)) {
            echo "<script>alert('Username tidak boleh kosong');location.href='login.php';</script>";
        } elseif (empty($password)) {
            echo "<script>alert('Password tidak boleh kosong');location.href='login.php';</script>";
        } else {
            $sql = "SELECT * FROM data_siswa WHERE username = '$username' AND password = '$password'";
            $qry_login = mysqli_query($connection, $sql);
            if (mysqli_num_rows($qry_login) > 0) {
                $dt_login = mysqli_fetch_array($qry_login);
                session_start();
                $_SESSION['id_siswa'] = $dt_login['id_siswa'];
                $_SESSION['nama_siswa'] = $dt_login['nama_siswa'];
                $_SESSION['status_login'] = true;
                header("location: home.php");
            } else {
                echo "<script>alert('Username dan Password tidak benar');location.href='login.php';</script>";
            }
        }
    }
}

function hapus_siswa($data)
{
    global $connection;

    $id_siswa = $data['id_siswa'];

    if ($id_siswa) {
        $sql = "DELETE FROM data_siswa WHERE id_siswa = '$id_siswa'";
        $delete = mysqli_query($connection, $sql);
        if ($delete) {
            echo "<script>alert('Sukses hapus siswa');location.href='tampil_siswa.php';</script>";
        } else {
            echo "<script>alert('Gagal hapus siswa');location.href='tampil_siswa.php';</script>";
        }
    }
}

function hapus_cart()
{
    session_start();
    unset($_SESSION['cart'][$_GET['id']]);
    header('location: keranjang.php');
}

function checkout()
{
    global $connection;
    session_start();

    $cart = @$_SESSION['cart'];
    $id_siswa = $_SESSION['id_siswa'];
    $getDate = date("Y-m-d");

    if (count($cart) > 0) {
        $lama_pinjam = 5; //satuan hari
        $tgl_harus_kembali = date('Y-m-d', mktime(0, 0, 0, date('m'), (date('d') + $lama_pinjam), date('Y')));
        $sql = "INSERT INTO peminjaman_buku(id_siswa, tanggal_pinjam, tanggal_kembali) VALUE ('$id_siswa', '$getDate', '$tgl_harus_kembali')";

        mysqli_query($connection, $sql);

        $id = mysqli_insert_id($connection);

        foreach ($cart as $key_produk => $val_produk) {
            $id_buku = $val_produk['id_buku'];
            $qty = $val_produk['qty'];

            $sql =  "INSERT INTO detail_peminjaman_buku(id_peminjaman_buku, id_buku, qty) VALUE ('$id', '$id_buku', $qty)";
            mysqli_query($connection, $sql);
        }

        unset($_SESSION['cart']);
        echo '<script>alert("Anda berhasil meminjam buku");location.href="histori_peminjaman.php"</script>';
    }
}

function add_to_cart($data)
{
    global $connection;

    session_start();
    if ($data) {

        $qry_get_buku = mysqli_query($connection, "select * from buku where id_buku = '" . $_GET['id_buku'] . "'");
        $dt_buku = mysqli_fetch_array($qry_get_buku);
        $_SESSION['cart'][] = array(
            'id_buku' => $dt_buku['id_buku'],
            'nama_buku' => $dt_buku['nama_buku'],
            'qty' => $_POST['jumlah_pinjam']
        );

    }
    header('location: keranjang.php');
}

function kembali($data)
{
    global $connection;

    if ($data['id']) {
        $id_peminjaman_buku = $data['id'];
        $cek_terlambat = mysqli_query($connection, "select * from peminjaman_buku where id_peminjaman_buku = '" . $id_peminjaman_buku . "' ");
        $dt_pinjam = mysqli_fetch_array($cek_terlambat);

        if (strtotime($dt_pinjam['tanggal_kembali']) >= strtotime(date('Y-m-d'))) {
            $denda = 0;
        } else {
            $harga_denda_perhari = 5000;
            $tanggal_kembali = new DateTime();
            $tgl_harus_kembali = new DateTime($dt_pinjam['tanggal_kembali']);
            $selisih_hari = $tanggal_kembali->diff($tgl_harus_kembali)->d;
            $denda = $selisih_hari * $harga_denda_perhari;
        }

        mysqli_query($connection, "insert into pengembalian_buku (id_peminjaman_buku, tanggal_pengembalian,denda) value('" . $id_peminjaman_buku . "','" . date('Y-m-d') . "','" . $denda . "')");
        header('location: histori_peminjaman.php');
    }
}

function hapus_history()
{

    if ($_GET['id_peminjaman_buku']) {
        include "koneksi.php";
        $qry_hapus = mysqli_query($conn, "delete from peminjaman_buku where id_peminjaman_buku='" . $_GET['id_peminjaman_buku'] . "'");
        if ($qry_hapus) {
            echo "<script>alert('Sukses hapus histori');location.href='histori_peminjaman.php';</script>";
        } else {
            echo "<script>alert('Gagal hapus histori');location.href='histori_peminjaman.php';</script>";
        }
    }
}
