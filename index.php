<?php
error_reporting(E_ALL);
ini_set("display_error", 1);

$message = '';

if(isset($_POST['add_to_cart']))
{
    if(isset($_COOKIE['shopping_cart']))
    {
        $cookie_data = $_COOKIE['shopping_cart'];

        $cart_data = json_decode($cookie_data, true);
    } else {

        $cart_data = array();
    }

    $item_list = array_column($cart_data, 'hidden_id');

    if(in_array($_POST["hidden_id"], $item_list))
    {
        foreach($cart_data as $k => $v)
        {
            if($cart_data[$k]["hidden_id"] == $_POST["hidden_id"])
            {
                $cart_data[$k]["quantity"] = $cart_data[$k]["quantity"] + $_POST["quantity"];
            }
        }
    }else {
        $item_array = array(
            'hidden_id' => $_POST['hidden_id'],
            'hidden_name' => $_POST['hidden_name'],
            'hidden_price' => $_POST['hidden_price'],
            'quantity' => $_POST['quantity']
        );
    
        $cart_data[] = $item_array;

} 

    $item_data = json_encode($cart_data);
    setcookie('shopping_cart', $item_data, time() + (86400 * 30));
    header("location:index.php?success=1");
}

if(isset($_GET["action"]) == "clear")
{
   setcookie("shopping_cart","", time() -3600);
   header("location:index.php?clearAll=1");
}
// [element2]
if(isset($_GET["action"]) == "delete")
{
    $cookie_data = stripslashes($_COOKIE['shopping_cart']);
    $cart_data = json_decode($cookie_data, true);
    foreach($cart_data as $k => $v) {
        if($cart_data[$k]["hidden_id"] == $_GET['id']) {
            unset($cart_data[$k]);
            $item_data = json_encode($cart_data);
            setcookie('shopping_cart', $item_data, time() + (86400 *30));
            header("location:index.php?remove=1");
        }
    }
}

if(isset($_GET['succes']))
{
    $message = '
    <div>
        le produit a ete ajouter avec succes
    </div>
    ';
}

if(isset($_GET['remove']))
{
    $message = '
    <div>
        le produit a ete enlever avec succes
    </div>
    ';
}

if(isset($_GET['clearAll']))
{
    $message = '
    <div>
        le panier a ete vider
    </div>
    ';
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fake Nike</title>
</head>
<style>
img {
max-height: 200px; 
max-width: 200px;
}


</style>

<body>
    <div class="title">    
        <h2>Fake Nike</h2>
    </div>
    <?php
        if(isset($_COOKIE['shopping_cart']))
        {
        //    print_r($_COOKIE['shopping_cart']);
        }
            echo $message;
        ?>
    <div class="form">
        <form method="post">
             <div class="product">
                <img src="../images/Janoski.jpeg" alt="Janoski">
                <p>Janoski</p>
                <p>90$</p>
                <input type="text" name="quantity" value="1">
                <input type="hidden" name="hidden_name" value="janoski">
                <input type="hidden" name="hidden_price" value="90">
                <input type="hidden" name="hidden_id" value="1">
                <input type="submit" value="Add to cart" name="add_to_cart">
            </div>
        </form>
    </div>
   
    <div class="form">
        <form method="post">
            <div class="product">
                <img src="../images/Jordan.jpeg" alt="Jordan">
                <p>Jordan</p>
                <p>250$</p>
                <input type="text" name="quantity" value="1">
                <input type="hidden" name="hidden_name" value="jordan">
                <input type="hidden" name="hidden_price" value="250">
                <input type="hidden" name="hidden_id" value="2">
                <input type="submit" value="Add to cart" name="add_to_cart">
            </div>
        </form>
    </div>  
    
    <div class="form">
        <form method="post">
            <div class="product">
                <img src="../images/Airforce.jpeg" alt="Airforce">
                <p>Airforce</p>
                <p>120$</p>
                <input type="text" name="quantity" value="1">
                <input type="hidden" name="hidden_name" value="airforce">
                <input type="hidden" name="hidden_price" value="120">
                <input type="hidden" name="hidden_id" value="3">
                <input type="submit" value="Add to cart" name="add_to_cart">
            </div>
        </form>
    </div>


<div style="clear:both">
   <p>Shopping cart</p>
   <table>
        <tr>
            <td>Name</td>
            <td>Quantity</td>
            <td>Price</td>
            <td>Total</td>
            <td>Action</td>
        </tr>
     <?php
        if(isset($_COOKIE['shopping_cart'])) {
            $total = 0;
            $cookie_data = stripslashes($_COOKIE['shopping_cart']);
            $cart_data = json_decode($cookie_data, true);
            foreach($cart_data as $k => $v)
            {
        

        ?>
        <tr>
                    <td><?php echo $v["hidden_name"]; ?></td>
                    <td><?php echo $v["quantity"]; ?></td>
                    <td><?php echo $v["hidden_price"]; ?></td>
                    <td><?php echo number_format($v["quantity"]*$v["hidden_price"],2) ?>$</td>
                    <td><a href="index.php?action=delete&id=<?php echo $v["hidden_id"];?>">Remove</a></td>
                </tr>
                <?php
        }
    } else {
        echo "<tr>Shopping cart is empty</tr>";
    }
        ?>



   </table>
   </div>
</body>
</html>