<?php
    session_start();
    //koneksi
    $koneksi = mysqli_connect(
        'localhost', 'root', '', 'tubes'
    );

    //login
    if(isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $cek = mysqli_query($koneksi, "select * from user where username = '$username' and password = '$password'");
        $hitung = mysqli_num_rows($cek);

        if($hitung>0){
            $_SESSION['login'] = "True";
            header('location: index.php');
        } else{
            echo '<script>
            alert("Username atau Password Salah");
            window.location.href="login.php"
            </script>';
        }
    }

    //stok
    if(isset($_POST['tambahbarang'])){
        $namaproduk = $_POST['namaproduk'];
        $deskripsi = $_POST['deskripsi'];
        $stock = $_POST['stock'];
        $harga = $_POST['harga'];

        $tambah = mysqli_query($koneksi, "insert into produk (namaproduk, deskripsi, harga, stock) values('$namaproduk', '$deskripsi', '$harga', '$stock')");

        if($tambah){
            header("location: stock.php");
        } else{
            echo '<script>
            alert("Gagal Menambah Data Barang");
            window.location.href="login.php"
            </script>';
        }
    }

    //pelanggan
    if(isset($_POST['tambahpelanggan'])){
        $namapelanggan = $_POST['namapelanggan'];
        $notelp = $_POST['notelp'];
        $alamat = $_POST['alamat'];

        $tambah = mysqli_query($koneksi, "insert into pelanggan (namapelanggan, notelp, alamat) values('$namapelanggan', '$notelp', '$alamat')");

        if($tambah){
            header("location: pelanggan.php");
        } else{
            echo '<script>
            alert("Gagal Menambah Data Pelanggan");
            window.location.href="pelanggan.php"
            </script>';
        }
    }

    //pesanan
    if(isset($_POST['tambahpesanan'])){
        $idpelanggan = $_POST['idpelanggan'];

        $tambah = mysqli_query($koneksi, "insert into pesanan (idpelanggan) values('$idpelanggan')");

        if($tambah){
            header("location: index.php");
        } else{
            echo '<script>
            alert("Gagal Menambah Data Pesanan");
            window.location.href="index.php"
            </script>';
        }
    }

    //stok baru
    if(isset($_POST['tambahpesananbaru'])){
        $idproduk = $_POST['idproduk'];
        $idp = $_POST['idp'];
        $qty = $_POST['qty']; //jumlah
        
        //hitung stok
        $hitung1 = mysqli_query($koneksi, "select * from produk where idproduk='$idproduk'");
        $hitung2 = mysqli_fetch_array($hitung1);
        $stockbarang = $hitung2['stock'];

        if($stockbarang >= $qty){
            //kurang stok
            $selisih = $stockbarang - $qty;

            //stok cukup
            $tambah = mysqli_query($koneksi, "insert into detailpesanan (idpesanan, idproduk, qty) values('$idp', '$idproduk', '$qty')");
            $update = mysqli_query($koneksi, "update produk set stock='$selisih' where idproduk='$idproduk'");

            if($tambah && $update){
                header("location: view.php?idp=".$idp);
            } else{
                 echo '<script>
                 alert("Gagal Menambah Data Pesanan");
                 window.location.href="view.php?idp='.$idp.'"
                 </script>';
            }
        } else{
            echo '<script>
                 alert("Stok Barang Tidak Cukup");
                 window.location.href="view.php?idp='.$idp.'"
                 </script>';
        }
    }

    //tambah barang masuk
    if(isset($_POST['barangmasuk'])){
        $idproduk = $_POST['idproduk'];
        $qty = $_POST['qty'];

        //cari tau stok sekarang
        $caristok = mysqli_query($koneksi, "select * from produk where idproduk='$idproduk'");
        $caristok2 = mysqli_fetch_array($caristok);
        $stocksekarang = $caristok2['stock']; 

        //hitung
        $stockbaru = $stocksekarang + $qty;

        $insert = mysqli_query($koneksi, "insert into masuk (idproduk, qty) values('$idproduk', '$qty')");
        $update = mysqli_query($koneksi, "update produk set stock='$stockbaru' where idproduk='$idproduk'");

        if($insert && $update){
            header("location: masuk.php");
        } else{
            echo '<script>
                 alert("Gagal");
                 window.location.href="masuk.php"
                 </script>';
        }
    }

    //edit barang
    if(isset($_POST['editbarang'])){
        $np = $_POST['namaproduk'];
        $desc = $_POST['deskripsi'];
        $harga = $_POST['harga'];
        $idp = $_POST['idp'];

        $query = mysqli_query($koneksi, "update produk set namaproduk='$np', deskripsi='$desc', harga='$harga' where idproduk='$idp'");
        if($query){
            header("location: stock.php");
        } else{
            echo '<script>
                 alert("Gagal");
                 window.location.href="stock.php"
                 </script>';
        }
    }

    //hapus barang
    if(isset($_POST['hapusbarang'])){
        $idp = $_POST['idp'];

        $query = mysqli_query($koneksi, "delete from produk where idproduk='$idp'");
        if($query){
            header("location: stock.php");
        } else{
            echo '<script>
                 alert("Gagal");
                 window.location.href="stock.php"
                 </script>';
        }
    }

    //edit pelanggan
    if(isset($_POST['editpelanggan'])){
        $np = $_POST['namapelanggan'];
        $nt = $_POST['notelp'];
        $alamat = $_POST['alamat'];
        $id = $_POST['idpl'];

        $query = mysqli_query($koneksi, "update pelanggan set namapelanggan='$np', notelp='$nt', alamat='$alamat' where idpelanggan='$id'");
        if($query){
            header("location: pelanggan.php");
        } else{
            echo '<script>
                 alert("Gagal");
                 window.location.href="pelanggan.php"
                 </script>';
        }
    }

    //hapus pelanggan
    if(isset($_POST['hapuspelanggan'])){
        $id = $_POST['idpl'];

        $query = mysqli_query($koneksi, "delete from pelanggan where idpelanggan='$id'");
        if($query){
            header("location: pelanggan.php");
        } else{
            echo '<script>
                 alert("Gagal");
                 window.location.href="pelanggan.php"
                 </script>';
        }
    }

    //edit data barang masuk
    if(isset($_POST['editbarangmasuk'])){
        $qty = $_POST['qty'];
        $idm = $_POST['idm'];
        $idp = $_POST['idp'];

        //caritau qty
        $search1 = mysqli_query($koneksi, "select * from masuk where idmasuk='$idm'");
        $search2 = mysqli_fetch_array($search1);
        $qtysekarang = $search2['qty'];

        //cari tau stok sekarang
        $caristok = mysqli_query($koneksi, "select * from produk where idproduk='$idp'");
        $caristok2 = mysqli_fetch_array($caristok);
        $stocksekarang = $caristok2['stock'];

        if($qty >= $qtysekarang){
            //kalau input user lbh besar drpd qty yg tercatat
            //hitung selisih
            $selisih = $qty - $qtysekarang;
            $stockbaru = $stocksekarang + $selisih;

            $query1 = mysqli_query($koneksi, "update masuk set qty='$qty' where idmasuk='$idm'");
            $query2 = mysqli_query($koneksi, "update produk set stock='$stockbaru' where idproduk='$idp'");
            if($query1 && $query2){
                header("location: masuk.php");
            } else{
                echo '<script>
                     alert("Gagal");
                     window.location.href="masuk.php"
                     </script>';
            }
        } else{
            //kalau lbh kecil
            $selisih = $qtysekarang -  $qty;
            $stockbaru = $stocksekarang - $selisih;

            $query1 = mysqli_query($koneksi, "update masuk set qty='$qty' where idmasuk='$idm'");
            $query2 = mysqli_query($koneksi, "update produk set stock='$stockbaru' where idproduk='$idp'");
            if($query1 && $query2){
                header("location: masuk.php");
            } else{
                echo '<script>
                     alert("Gagal");
                     window.location.href="masuk.php"
                     </script>';
            }
        }
    }

    //hapus data barang masuk
    if(isset($_POST['hapusbarangmasuk'])){
        $idp = $_POST['idp'];
        $idm = $_POST['idm'];

        //caritau qty
        $search1 = mysqli_query($koneksi, "select * from masuk where idmasuk='$idm'");
        $search2 = mysqli_fetch_array($search1);
        $qtysekarang = $search2['qty'];

        //cari tau stok sekarang
        $caristok = mysqli_query($koneksi, "select * from produk where idproduk='$idp'");
        $caristok2 = mysqli_fetch_array($caristok);
        $stocksekarang = $caristok2['stock'];        
        
        $stockbaru = $stocksekarang - $qtysekarang;

        $query1 = mysqli_query($koneksi, "delete from masuk where idmasuk='$idm'");
        $query2 = mysqli_query($koneksi, "update produk set stock='$stockbaru' where idproduk='$idp'");
        if($query1 && $query2){
            header("location: masuk.php");
        } else{
            echo '<script>
                    alert("Gagal");
                    window.location.href="masuk.php"
                    </script>';
        }
    }

    //hapus pesanan
    if(isset($_POST['hapuspesanan'])){
        $ido = $_POST['ido'];

        $cekdata = mysqli_query($koneksi, "select * from detailpesanan dp where idpesanan='$ido'");

        while($confirm = mysqli_fetch_array($cekdata)){
            //balikin stok
            $qty = $confirm['qty'];
            $idproduk = $confirm['idproduk'];
            $iddp = $confirm['iddetailpesanan'];

            //cari tau stok sekarang
            $caristok = mysqli_query($koneksi, "select * from produk where idproduk='$idproduk'");
            $caristok2 = mysqli_fetch_array($caristok);
            $stocksekarang = $caristok2['stock']; 

            $stockbaru = $stocksekarang + $qty;

            $queryupdate = mysqli_query($koneksi, "update produk set stock='$stockbaru' where idproduk='$idproduk'");

            //hapus data
            $querydelete = mysqli_query($koneksi, "delete from detailpesanan where iddetailpesanan='$iddp'");

        }

        $query = mysqli_query($koneksi, "delete from pesanan where idorder='$ido'");

        if($queryupdate && $querydelete && $query){
            header("location: index.php");
        } else{
            echo '<script>
                 alert("Gagal");
                 window.location.href="index.php"
                 </script>';
        }
    }
?>