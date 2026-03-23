
const title = document.getElementById("title");
const description = document.getElementById("description");
const amount = document.getElementById("amount");
const target = document.getElementById("target");
const bar = document.getElementById("bar");
const link = document.getElementById("link");
function checkDetails(data)
{
    console.log(data);
    title.innerHTML = data[1];
    description.innerHTML = data[2];
    amount.innerHTML = data[3] + "EGP / " + data[4] + "EGP";
    target.innerHTML = Math.round((data[3] / data[4]) * 100) + '% reached <br><i class="fa-solid fa-caret-down"></i>';
    target.style.left = Math.round((data[3] / data[4]) * 100) + "%";
    bar.style.width = (data[3] / data[4]) * 100 + "%";
    link.href = "Donation.php?id=" + data[0];
}