<h1>WooCommerce Variation stock based on parent stock</h1>

<p>This function causes the stock of product variations to be based on the stock of the parent product according to the stock value that a variation represents for example: a variation that represents 20 products will remove 20 units from the parent's stock and the stock of this same variance will be the parent product's total inventory value divided by 20.</p>

<h2>Sumary</h2>

<ul>
  <li><a href="#howToUse">How to use this in your project</a></li>
  <li><a href="#howItWorks">How it works?</a></li>
</ul>

<h2 id="howToUse">How to use this in your project</h2>

<p>First copy and paste the code above into your theme's functions.php file, then it will be necessary to change some values in the code.</p>

<img src="images/values.png" alt="values_image">

<h3>$product_category_name</h3>

<p>This variable is the name of a category inserted in the parent product, it can be anything you want, its function is to make only some products on your wordpress site be affected and not all existing variable products.</p>

<h3>$value1, $value2, $value3, ...</h3>

<p>This variable is a string with the values it has in the name of the attributes of the variations, maybe with this image it will be clearer:</p>

<h2 id="howItWorks">How it works?</h2>
