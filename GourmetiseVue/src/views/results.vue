<template>
  <div class="resultats-container">
    <h1 class="titre">Résultat du concours</h1>

    <!-- Vue ADMIN -->
    <template v-if="isAdmin">
      <table class="table-resultats">
        <thead>
          <tr>
            <th>Classement</th>
            <th>Boulangerie</th>
            <th>Accueil</th>
            <th>Présentation</th>
            <th>Qualité des produits</th>
            <th>Note finale</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(bakery, index) in resultatAPI" :key="bakery.id">
            <td>{{ index + 1 }}</td>
            <td>{{ bakery.company_name }}</td>
            <td>{{ Number(bakery.note_w).toFixed(1) }}</td>
            <td>{{ Number(bakery.note_s).toFixed(1) }}</td>
            <td>{{ Number(bakery.note_p).toFixed(1) }}</td>
            <td>{{ Number(bakery.moyenne).toFixed(1) }}</td>
          </tr>
        </tbody>
      </table>
      <v-btn color="primary" @click="fetchGet"> Générer les résultats </v-btn>
      <v-btn color="primary" @click="published"> Publier le résultat </v-btn>
    </template>

    <!-- Vue BAKER -->
    <template v-if="isBaker">
      <template v-if="publish">
        <h2>Classement général</h2>
        <table class="table-resultats">
          <thead>
            <tr>
              <th>Classement</th>
              <th>Boulangerie</th>
              <th>Accueil</th>
              <th>Présentation</th>
              <th>Qualité des produits</th>
              <th>Note finale</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(bakery, index) in resultatAPI.slice(0, 3)"
              :key="bakery.id"
            >
              <td>{{ index + 1 }}</td>
              <td>{{ bakery.company_name }}</td>
              <td>{{ Number(bakery.note_w).toFixed(1) }}</td>
              <td>{{ Number(bakery.note_s).toFixed(1) }}</td>
              <td>{{ Number(bakery.note_p).toFixed(1) }}</td>
              <td>{{ Number(bakery.moyenne).toFixed(1) }}</td>
            </tr>
          </tbody>
        </table>

        <h2 v-if="resultatBySiret">Vos résultats</h2>
        <table v-if="resultatBySiret" class="table-resultats">
          <thead>
            <tr>
              <th>Classement</th>
              <th>Boulangerie</th>
              <th>Accueil</th>
              <th>Présentation</th>
              <th>Qualité des produits</th>
              <th>Note finale</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{{ classement }}</td>
              <td>{{ resultatBySiret.company_name }}</td>
              <td>{{ Number(resultatBySiret.note_w).toFixed(1) }}</td>
              <td>{{ Number(resultatBySiret.note_s).toFixed(1) }}</td>
              <td>{{ Number(resultatBySiret.note_p).toFixed(1) }}</td>
              <td>{{ Number(resultatBySiret.moyenne).toFixed(1) }}</td>
            </tr>
          </tbody>
        </table>
      </template>
      <template v-else>
        <p class="message-attente">
          Le classement n'a pas encore été publié, revenez plus tard !
        </p>
      </template>
    </template>

    <!-- Vue VISITEUR -->
    <template v-if="isVisitor">
      <template v-if="publish">
        <table class="table-resultats">
          <thead>
            <tr>
              <th>Classement</th>
              <th>Boulangerie</th>
              <th>Accueil</th>
              <th>Présentation</th>
              <th>Qualité des produits</th>
              <th>Note finale</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(bakery, index) in resultatAPI.slice(0, 3)"
              :key="bakery.id"
            >
              <td>{{ index + 1 }}</td>
              <td>{{ bakery.company_name }}</td>
              <td>{{ Number(bakery.note_w).toFixed(1) }}</td>
              <td>{{ Number(bakery.note_s).toFixed(1) }}</td>
              <td>{{ Number(bakery.note_p).toFixed(1) }}</td>
              <td>{{ Number(bakery.moyenne).toFixed(1) }}</td>
            </tr>
          </tbody>
        </table>
      </template>
      <template v-else>
        <p class="message-attente">
          Le classement n'a pas encore été publié, revenez plus tard !
        </p>
      </template>
    </template>
  </div>
</template>

<script setup>
import axios from "axios";
import { ref, computed, onMounted } from "vue";

const userRole = ref(localStorage.getItem("role"));
const isAdmin = computed(() => userRole.value === "ROLE_ADMIN");
const isBaker = computed(() => userRole.value === "ROLE_BAKER");
const siret = ref(localStorage.getItem("idBakery"));

const publish = ref(localStorage.getItem("isPublished") === "true");
const resultatAPI = ref(JSON.parse(localStorage.getItem("resultatData")));
const resultatBySiret = ref();
const isVisitor = computed(() => !isAdmin.value && !isBaker.value);

onMounted(() => {
  fetchGet();
  getResultatBySiret();
});

const classement = computed(() => {
  const index = resultatAPI.value.findIndex(
    (b) => b.bakery_siret === siret.value,
  );
  return index !== -1 ? index + 1 : "N/A";
});

function published() {
  publish.value = !publish.value;
  localStorage.setItem("isPublished", publish.value);
  localStorage.setItem("resultatData", JSON.stringify(resultatAPI.value));
}

async function fetchGet() {
  try {
    const response = await axios.get("http://localhost:8000/api/evaluation");
    resultatAPI.value = response.data;
  } catch (error) {
    console.error(error);
  }
}

async function getResultatBySiret() {
  try {
    const response = await axios.get(
      `http://localhost:8000/api/evaluation/${siret.value}`,
    );
    resultatBySiret.value = response.data[0];
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
