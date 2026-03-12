<template>
  <button>
    SELECT SUM(welcome)/COUNT(bakery_id) AS note_w,
    SUM(shop_presentation)/COUNT(bakery_id) AS note_s,
    SUM(product_quality)/COUNT(bakery_id) AS note_p,
    ROUND((SUM(welcome)+SUM(shop_presentation)+SUM(product_quality))/(COUNT(bakery_id)*3),2) AS moyenne
    FROM evaluation
    GROUP BY bakery_id
    ORDER BY moyenne DESC,
    SUM(product_quality) DESC,
    SUM(welcome) DESC,
    SUM(shop_presentation) DESC;
  </button>
</template>