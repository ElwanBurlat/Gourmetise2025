<template>
<<<<<<< HEAD
  <div class="resultats-container">
    <h1 class="titre">Résultat du concours</h1>

    <table class="table-resultats">
      <thead>
        <tr>
          <th>Boulangerie</th>
          <th>Accueil</th>
          <th>Présentation</th>
          <th>Qualité des produits</th>
          <th>Note finale</th>
        </tr>
      </thead>

      <tbody>
         <tr>
          <td>{{ bakery }}</td>
          <td>{{ note_w }}</td>
          <td>{{ note_s }}</td>
          <td>{{ note_p }}</td>
          <td>{{ moyenne}}</td>
        </tr>
      </tbody>
    </table>
  </div>
  <v-btn color="primary" @click="fetchGet">Generation concours !</v-btn>

</template>

<script setup>
import axios from 'axios'
import { ref } from 'vue'
const bakery= ref('')
const note_w = ref('')
const note_s = ref('')
const note_p = ref('')
const moyenne = ref('')
  async function fetchGet() {
  try {
    const  resultatAPI= await axios.get('http://localhost:8000/api/resultat');
    const bakeryAPI = await axios.get('http://localhost:8000/api/bakery');

    bakery.value = bakeryAPI.data.bakery;

    note_w.value = resultatAPI.data.note_w;
    note_s.value = resultatAPI.data.note_s;
    note_p.value = resultatAPI.data.note_p;

    


    }
    catch (error) {
    console.error(error)
  }
}

</script>

<style scoped>

.resultats-container{
  width: 85%;
  margin: 50px auto;
  text-align: center;
}

.titre{
  margin-bottom: 30px;
  font-size: 32px;
  color: #2c3e50;
}

.table-resultats{
  width: 100%;
  border-collapse: collapse;
  background: white;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.table-resultats th{
  background-color: #2d6fb7;
  color: white;
  padding: 14px;
  font-size: 16px;
}

.table-resultats td{
  padding: 12px;
  border-bottom: 1px solid #ddd;
}

.table-resultats tr:hover{
  background-color: #f5f5f5;
}

</style>
=======
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
>>>>>>> e7bd7bcaef4d3a1311a2676b1c66ac3b223565d1
