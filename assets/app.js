import "./bootstrap.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
//Je prend le css de bootstrap
import "./vendor/bootstrap/dist/css/bootstrap.min.css";
// ça c'est mon css
import "./styles/app.css";
// Je prend le JS de bootstrap
import "bootstrap";

console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");
