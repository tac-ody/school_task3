<!-- 交通費1行ごとの合計 -->
<?php
$element1 =  $_POST['firstPrice'];
for ($i = 0; $i < count($element1); $i++) {
    $price1 = $_POST['firstPrice'][$i];
    $route = $_POST['pattern'][$i];
    if($_POST['koutuuhi'][$i] == ""){
        $sum1[] = '' ;
    }elseif($_POST['pattern'][$i] == ""){
        $sum1[] = '' ;
    }elseif($_POST['pattern'][$i] == "往復"){
        $sum1[] = (int)$price1 * 2;
    }elseif($_POST['pattern'][$i] == "片道"){
        $sum1[] = (int)$price1;
    }
}
?>
<!-- 交通費5行分の合計 -->
<?php
$getTotal1 = array_sum($sum1);
?>
<!-- 宿泊費1行ごとの合計 -->
<?php
$element2 =  $_POST['secondPrice'];
for ($i = 0; $i < count($element2); $i++) {
    $price2 = $_POST['secondPrice'][$i];
    $quantity_stayEatdrink = $_POST['quantity'][$i];
    if($_POST['stay_eatdrink'][$i] == ""){
        $sum2[] = '' ;
    }elseif($_POST['quantity'][$i] == ""){
        $sum2[] = '' ;
    }elseif(isset($_POST['quantity'][$i])){
        $sum2[] = (int)$price2 * (int)$quantity_stayEatdrink;
    }
}
?>
<!-- 宿泊費5行分の合計 -->
<?php
$getTotal2 = array_sum($sum2);
?>
<!-- その他1行ごとの合計 -->
<?php
$element3 =  $_POST['thirdPrice'];
for ($i = 0; $i < count($element3); $i++) {
    $price3 = $_POST['thirdPrice'][$i];
    $quantityOther = $_POST['quantity_other'][$i];
    if($_POST['productName'][$i] == ""){
        $sum3[] = '' ;
    }elseif($_POST['quantity_other'][$i] == ""){
        $sum3[] = '' ;
    }elseif(isset($_POST['quantity_other'][$i])){
        $sum3[] = (int)$price3 * (int)$quantityOther;
    }
}
?>
<!-- その他5行分の合計 -->
<?php
$getTotal3 = array_sum($sum3);
?>
<!-- 総合計 -->
<?php
$grandTotral = $getTotal1 + $getTotal2 + $getTotal3;
?>

<!-- 部署 -->
<?php
    $busyo = $_POST['busyo'];
?>
<!-- 交通機関 -->
<?php
for ($i = 0; $i < count($_POST['firstPrice']); $i++) {
    if($_POST['koutuuhi'][$i] && $_POST['pattern'][$i] && $_POST['firstPrice'][$i] == ""){
        continue;
    }elseif($_POST['koutuuhi'][$i] == ""){
        continue;
    }elseif($_POST['pattern'][$i] == ""){
        continue;
    }elseif($_POST['firstPrice'][$i] == ""){
        continue;
    }else{
        $koutuuhi[] =$_POST['koutuuhi'][$i];
    }
}
?>
<!-- 利用区分 -->
<?php
for ($i = 0; $i < count($_POST['firstPrice']); $i++) {
    if($_POST['pattern'][$i] && $_POST['koutuuhi'][$i] && $_POST['firstPrice'][$i] == ""){
        continue;
    }elseif($_POST['koutuuhi'][$i] == ""){
        continue;
    }elseif($_POST['pattern'][$i] == ""){
        continue;
    }elseif($_POST['firstPrice'][$i] == ""){
        continue;
    }else{
        $pattern[] =$_POST['pattern'][$i];
    }
}
?>
<!-- 宿泊等 -->
<?php
for ($i = 0; $i < count($_POST['stay_eatdrink']); $i++) {
        $stay_eatdrink[] = $_POST['stay_eatdrink'][$i];
}
?>
<!-- 泊数（数量） -->
<?php
for ($i = 0; $i < count($_POST['quantity']); $i++) {
    $quantity[] =$_POST['quantity'][$i];
}
?>
<!-- 数量 -->
<?php
for ($i = 0; $i < count($_POST['quantity_other']); $i++) {
    $quantity_other[] =$_POST['quantity_other'][$i];
}
?>

<!-- csv書き出し -->
<?php
$total1 = array("$getTotal1");
$total2 = array("$getTotal2");
$total3 = array("$getTotal3");
$ary =  array($_POST['koutuuhi'] ,$_POST['firstPrice'] ,$_POST['pattern'],
             $sum1 ,$total1 ,$_POST['stay_eatdrink'] ,$_POST['secondPrice'],
             $_POST['quantity'] ,$sum2 ,$total2 ,$_POST['productName'] ,
             $_POST['thirdPrice'] ,$_POST['quantity_other'] ,$sum3 ,$total3
           );
//csvの文字化け対策
$ary = mb_convert_encoding($ary, "SJIS-win","UTF-8");
// ファイルを書き込み用に開く
$f = fopen("出張旅費精算.csv", "w");
// 正常にファイルを開くことができていれば書き込み
if ( $f ) {
  // $ary から順番に配列を呼び出して書き込み
  foreach($ary as $line){
    // fputcsv関数でファイルに書き込み
    fputcsv($f , $line);
  }
}
// csvファイルを閉じる
fclose($f);
?>

<!DOCTYPE html>
<html lang="jp" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>出張旅費 計算結果</title>
        <link rel="stylesheet" type="text/css" href="taskresult.css">
    </head>
    <body>
        <div id="header">
            <h1>出張旅費精算支援ツール*精算結果*</h1>
            <div class="header-busyo">部署名:
                <select name="busyo" class="box" readonly>
                    <option value=""><?php echo $busyo ?></option>
                </select>
            </div>
            <div class="header-name">名前:
                <input type="text" class="box" value="<?php print $_POST['name']?>" readonly>
            </div>
        </div>
        <!-- 交通費 -->
        <div id="contents1">
            <h2>交通費</h2>
            <div class="contents-header1">
                <p>交通機関</p>
                <p>単価</p>
                <p>利用区分</p>
                <p>合計</p>
            </div>
            <?php for($i=0;$i<count($koutuuhi);$i++){  ?>
                <div class="contents-main1">
                    <select name="koutuuhi[]" class="box1" readonly>
                        <option value="<?php echo $koutuuhi[$i]?>"><?php echo $koutuuhi[$i]?></option>
                    </select>
                </div>
                <div class="contents-main1">
                    <input type="text" class="first_price_box" name ="firstPrice[]" value="<?php echo $_POST['firstPrice'][$i]?>" readonly>
                </div>
                <div class="contents-main1">
                    <select name="pattern[]" class="first_quantity_box" readonly>
                        <option value="<?php echo $pattern[$i]?>"><?php echo $pattern[$i]?></option>
                    </select>
                </div>
                <div class="contents-main1">
                    <input type="text" class="first_total_box" name ="firstTotal[]" value="<?php echo $sum1[$i]?>" readonly>
                </div>
            <?php } ?>
            <div id="sum1">
                <div class="sum">合計　
                    <input type="text" id="sum-box1" value=<?php echo $getTotal1 ?> readonly>
                </div>
            </div>
        </div>
        <!-- 宿泊費 -->
        <!-- 1行目 -->
        <br>
        <div id="contents1">
            <h2>宿泊費</h2>
            <div class="contents-header1">
                <p>宿泊等</p>
                <p>単価</p>
                <p>泊数（数量）</p>
                <p>合計</p>
            </div>
            <div class="contents-main1">
                <select name="stay_eatdrink[]" class="box1" readonly>
                    <option value="<?php echo $stay_eatdrink[0]?>"><?php echo $stay_eatdrink[0]?></option>
                </select>
            </div>
            <div class="contents-main1">
                <input type="text" class="second_price_box" name ="secondPrice[]" value="<?php print $_POST['secondPrice'][0]?>" readonly>
            </div>
            <div class="contents-main1">
                <select name="quantity[]" class="second_quantity_box" readonly>
                    <option value="<?php echo $quantity[0]?>"><?php echo $quantity[0]?></option>
                </select>
            </div>
            <div class="contents-main1">
                <input type="text" class="second_total_box" name ="secondTotal[]" value="<?php echo $sum2[0]?>" readonly>
            </div>
            <!-- 2行目 -->
            <div class="contents-main2">
                <select name="stay_eatdrink[]" class="box1" readonly>
                    <option value="<?php echo $stay_eatdrink[1]?>"><?php echo $stay_eatdrink[1]?></option>
                </select>
            </div>
            <div class="contents-main2">
                <input type="text" class="second_price_box" name ="secondPrice[]" value="<?php print $_POST['secondPrice'][1]?>" readonly>
            </div>
            <div class="contents-main2">
                <select name="quantity[]" class="second_quantity_box" readonly>
                    <option value="<?php echo $quantity[1]?>"><?php echo $quantity[1]?></option>
                </select>
            </div>
            <div class="contents-main2">
                <input type="text" class="second_total_box" name ="secondTotal[]" value="<?php echo $sum2[1]?>" readonly>
            </div>
            <!-- 3行目 -->
            <div class="contents-main1">
                <select name="stay_eatdrink[]" class="box1" readonly>
                    <option value="<?php echo $stay_eatdrink[2]?>"><?php echo $stay_eatdrink[2]?></option>
                </select>
            </div>
            <div class="contents-main1">
                <input type="text" class="second_price_box" name ="secondPrice[]" value="<?php print $_POST['secondPrice'][2]?>" readonly>
            </div>
            <div class="contents-main1">
                <select name="quantity[]" class="second_quantity_box" readonly>
                    <option value="<?php echo $quantity[2]?>"><?php echo $quantity[2]?></option>
                </select>
            </div>
            <div class="contents-main1">
                <input type="text" class="second_total_box" name ="secondTotal[]" value="<?php echo $sum2[2]?>" readonly>
            </div>
            <!-- 4行目 -->
            <div class="contents-main2">
                <select name="stay_eatdrink[]" class="box1" readonly>
                    <option value="<?php echo $stay_eatdrink[3]?>"><?php echo $stay_eatdrink[3]?></option>
                </select>
            </div>
            <div class="contents-main2">
                <input type="text" class="second_price_box" name ="secondPrice[]" value="<?php print $_POST['secondPrice'][3]?>" readonly>
            </div>
            <div class="contents-main2">
                <select name="quantity[]" class="second_quantity_box" readonly>
                    <option value="<?php echo $quantity[3]?>"><?php echo $quantity[3]?></option>
                </select>
            </div>
            <div class="contents-main2">
                <input type="text" class="second_total_box" name ="secondTotal[]" value="<?php echo $sum2[3]?>" readonly>
            </div>
            <!-- 5行目 -->
            <div class="contents-main1">
                <select name="stay_eatdrink[]" class="box1" readonly>
                    <option value="<?php echo $stay_eatdrink[4]?>"><?php echo $stay_eatdrink[4]?></option>
                </select>
            </div>
            <div class="contents-main1">
                <input type="text" class="second_price_box" name ="secondPrice[]" value="<?php print $_POST['secondPrice'][4]?>" readonly>
            </div>
            <div class="contents-main1">
                <select name="quantity[]" class="second_quantity_box" readonly>
                    <option value="<?php echo $quantity[4]?>"><?php echo $quantity[4]?></option>
                </select>
            </div>
            <div class="contents-main1">
                <input type="text" class="second_total_box" name ="secondTotal[]" value="<?php echo $sum2[4]?>" readonly>
            </div>
            <div id="sum2">
                <div class="sum">合計　
                    <input type="text" id="sum-box2" value="<?php echo $getTotal2 ?>" readonly>
                </div>
            </div>
        </div>
        <!-- その他 -->
        <!-- 1行目 -->
        <br>
        <div id="contents1">
            <h2>その他</h2>
            <div class="contents-header1">
                <p>用途、商品名等</p>
                <p>単価</p>
                <p>数量</p>
                <p>合計</p>
            </div>
            <div class="contents-main1">
                <input type="text" class="box1" name="productName[]" value="<?php print $_POST['productName'][0]?>" readonly>
            </div>
            <div class="contents-main1">
                <input type="text" class="third_price_box" name="thirdPrice[]" value="<?php print $_POST['thirdPrice'][0]?>" readonly>
            </div>
            <div class="contents-main1">
                <select name="quantity_other[]" class="third_quantity_box" readonly>
                    <option value="<?php echo $quantity_other[0]?>"><?php echo $quantity_other[0]?></option>
                </select>
            </div>
            <div class="contents-main1">
                <input type="text" class="third_total_box" name ="thirdTotal[]" value="<?php echo $sum3[0]?>" readonly>
            </div>
            <!-- 2行目 -->
            <div class="contents-main2">
                <input type="text" class="box1" name="productName[]" value="<?php print $_POST['productName'][1]?>" readonly>
            </div>
            <div class="contents-main2">
                <input type="text" class="third_price_box" name="thirdPrice[]" value="<?php print $_POST['thirdPrice'][1]?>" readonly>
            </div>
            <div class="contents-main2">
                <select name="quantity_other[]" class="third_quantity_box" readonly>
                    <option value="<?php echo $quantity_other[1]?>"><?php echo $quantity_other[1]?></option>
                </select>
            </div>
            <div class="contents-main2">
                <input type="text" class="third_total_box" name ="thirdTotal[]" value="<?php echo $sum3[1]?>" readonly>
            </div>
            <!-- 3行目 -->
            <div class="contents-main1">
                <input type="text" class="box1" name="productName[]" value="<?php print $_POST['productName'][2]?>" readonly>
            </div>
            <div class="contents-main1">
                <input type="text" class="third_price_box" name="thirdPrice[]" value="<?php print $_POST['thirdPrice'][2]?>" readonly>
            </div>
            <div class="contents-main1">
                <select name="quantity_other[]" class="third_quantity_box" readonly>
                    <option value="<?php echo $quantity_other[2]?>"><?php echo $quantity_other[2]?></option>
                </select>
            </div>
            <div class="contents-main1">
                <input type="text" class="third_total_box" name ="thirdTotal[]" value="<?php echo $sum3[2]?>" readonly>
            </div>
            <!-- 4行目 -->
            <div class="contents-main2">
                <input type="text" class="box1" name="productName[]" value="<?php print $_POST['productName'][3]?>" readonly>
            </div>
            <div class="contents-main2">
                <input type="text" class="third_price_box" name="thirdPrice[]" value="<?php print $_POST['thirdPrice'][3]?>" readonly>
            </div>
            <div class="contents-main2">
                <select name="quantity_other[]" class="third_quantity_box" readonly>
                    <option value="<?php echo $quantity_other[3]?>"><?php echo $quantity_other[3]?></option>
                </select>
            </div>
            <div class="contents-main2">
                <input type="text" class="third_total_box" name ="thirdTotal[]" value="<?php echo $sum3[3]?>" readonly>
            </div>
            <!-- 5行目 -->
            <div class="contents-main1">
                <input type="text" class="box1" name="productName[]" value="<?php print $_POST['productName'][4]?>" readonly>
            </div>
            <div class="contents-main1">
                <input type="text" class="third_price_box" name="thirdPrice[]" value="<?php print $_POST['thirdPrice'][4]?>" readonly>
            </div>
            <div class="contents-main1">
                <select name="quantity_other[]" class="third_quantity_box" readonly>
                    <option value="<?php echo $quantity_other[4]?>"><?php echo $quantity_other[4]?></option>
                </select>
            </div>
            <div class="contents-main1">
                <input type="text" class="third_total_box" name ="thirdTotal[]" value="<?php echo $sum3[4]?>" readonly>
            </div>
            <div id="sum3">
                <div class="sum">合計　
                    <input type="text" id="sum-box3" value="<?php echo $getTotal3 ?>" readonly>
                </div>
            </div>
        </div>
        <footer>
            <input type="button" id="cancel-box" onclick="history.back()" value="戻る">
            <p>総合計</p>
            <input type="text" id="grandTotal" value="<?php echo $grandTotral ?>" readonly>
            <button type="submit" id="submit-box">OK</button>
        </footer>
    </body>
</html>
