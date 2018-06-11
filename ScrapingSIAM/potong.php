<?php
include 'wswl.php';
class DataSiam{
	public function get_data($nim){
		$pass=substr($nim,7);
		$sc = new Scrape("p$nim",$pass);
		$data = $sc->login();
		$data2=htmlspecialchars($data);

		//get nama
		$pnm=strpos($data, "bio-name")+10;
		$pna=strpos($data, "</div>",$pnm);
		$nama=substr($data, $pnm,$pna-$pnm);

		//get fakultas
		$pfm=strpos($data, "Jenjang/Fakultas")+56;
		$pfa=strpos($data, "</div>",$pfm);
		$fak=substr($data, $pfm,$pfa-$pfm);

		//get jurusan
		$pjm=strpos($data, "Jurusan")+47;
		$pja=strpos($data, "</div>",$pjm);
		$jur=substr($data, $pjm,$pja-$pjm);

		//get jurusan
		$ppm=strpos($data, "Program Studi")+53;
		$ppa=strpos($data, "</div>",$ppm);
		$pro=substr($data, $ppm,$ppa-$ppm);

		//get seleksi
		$psm=strpos($data, "Seleksi")+47;
		$psa=strpos($data, "</div>",$psm);
		$sel=substr($data, $psm,$psa-$psm);

		//get nilai
		$data3=substr($data2, strpos($data2,"END OF POP UP"));
		$aaa= strpos($data3,"printkhs")+140;
		$bbb=strpos($data3, "Waktu Eksekusi");
		$data3=substr($data3,$aaa,$bbb-$aaa);
		$DOM = new DOMDocument('1.0', 'UTF-8');
		$internalErrors = libxml_use_internal_errors(true);
		$DOM->loadHTML(htmlspecialchars_decode($data3));
		libxml_use_internal_errors($internalErrors);
		$Header = $DOM->getElementsByTagName('tr');
		foreach($Header as $NodeHeader)
		{
			$aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
		}

		if (substr(htmlspecialchars($nama),0,2)=="PE") {
			return "Akun Di LOCK";
		}else{
			$data="Nama : ".htmlspecialchars($nama)
			."\nFakultas : ".htmlspecialchars($fak)
			."\nJurusan : ".htmlspecialchars($jur)
			."\nProgram Studi : ".htmlspecialchars($pro)
			."\nSeleksi : ".htmlspecialchars($sel)
			."\n==================\n";
			for ($i=1; $i < count($aDataTableHeaderHTML)-5; $i++) {
				$matkul=explode("      ",$aDataTableHeaderHTML[$i]);
				$data=$data."-----no : ".$matkul[0]."-----\n"
				."Kode : ".substr($matkul[1],2)."  ".substr($matkul[3],2)." sks\n"
				."Matkul : ".substr($matkul[2],5)."\n"
				."Nilai : ".substr($matkul[4],2)."\n";
			}
			$data=$data."==================\n";
			for ($i=count($aDataTableHeaderHTML)-4; $i < count($aDataTableHeaderHTML); $i++) {
				$matkul=explode("      ",$aDataTableHeaderHTML[$i]);
				$data=$data.$matkul[2]
				." ".substr($matkul[3],0)
				."\n";
			}

			$data=$data."==================";
			return $data;
		}
	}
}
?>
