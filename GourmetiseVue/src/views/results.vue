<template>
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
        <tr v-for="bakery in resultatAPI" :key="bakery.id">
          <td>{{ bakery.company_name }}</td>
          <td>{{ Number(bakery.note_w).toFixed(1) }}</td>
          <td>{{ Number(bakery.note_s).toFixed(1) }}</td>
          <td>{{ Number(bakery.note_p).toFixed(1) }}</td>
          <td>{{ Number(bakery.moyenne).toFixed(1) }}</td>
        </tr>
      </tbody>
    </table>
  </div>
  <v-btn class="" color="primary" @click="fetchGet">
    Generation résultats !
  </v-btn>
</template>

<script setup>
import axios from "axios";
import { ref } from "vue";

const resultatAPI = ref([]);

async function fetchGet() {
  try {
    const response = await axios.get("http://localhost:8000/api/evaluation");
    resultatAPI.value = response.data;
    console.log(resultatAPI.value);
  } catch (error) {
    console.error(error);
  }
}
</script>

<style scoped>
.resultats-container {
  width: 85%;
  margin: 50px auto;
  text-align: center;
}

.titre {
  margin-bottom: 30px;
  font-size: 32px;
  color: #2c3e50;
}

.table-resultats {
  width: 100%;
  border-collapse: collapse;
  background: white;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.table-resultats th {
  background-color: #2d6fb7;
  color: white;
  padding: 14px;
  font-size: 16px;
}

.table-resultats td {
  padding: 12px;
  border-bottom: 1px solid #ddd;
}

.table-resultats tr:hover {
  background-color: #f5f5f5;
}
</style>
