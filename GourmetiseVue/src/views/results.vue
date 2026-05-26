<template>
  <div class="resultats-container">
    <div class="header">
      <h1 class="titre">🏆 Résultats du concours</h1>
      <p class="sous-titre">Classement des meilleures boulangeries</p>
    </div>
    <template v-if="isAdmin">
      <div class="table-wrapper">
        <table class="table-resultats">
          <thead>
            <tr>
              <th>#</th>
              <th>Boulangerie</th>
              <th>Accueil</th>
              <th>Présentation</th>
              <th>Qualité</th>
              <th>Note finale</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(bakery, index) in resultatAPI"
              :key="bakery.id"
              :class="'rank-' + (index + 1)"
            >
              <td>
                <span class="badge">
                  {{
                    index === 0
                      ? "🥇"
                      : index === 1
                        ? "🥈"
                        : index === 2
                          ? "🥉"
                          : index + 1
                  }}
                </span>
              </td>
              <td class="bakery-name">{{ bakery.company_name }}</td>
              <td>
                <span class="note">{{ Number(bakery.note_w).toFixed(1) }}</span>
              </td>
              <td>
                <span class="note">{{ Number(bakery.note_s).toFixed(1) }}</span>
              </td>
              <td>
                <span class="note">{{ Number(bakery.note_p).toFixed(1) }}</span>
              </td>
              <td>
                <span class="note finale">{{
                  Number(bakery.moyenne).toFixed(1)
                }}</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="btn-group">
        <div
          v-if="resultatContestParam?.statusLabel === 'Terminé' && !isGenerated"
        >
          <v-btn color="primary" @click="fetchGet">Générer les résultats</v-btn>
        </div>
        <div v-if="isGenerated && !publish">
          <v-btn color="success" @click="published">Publier le résultat</v-btn>
        </div>
        <div v-if="publish">
          <v-chip color="success" prepend-icon="mdi-check-circle">
            Concours déjà publié
          </v-chip>
        </div>
      </div>
    </template>
    <template v-if="isBaker">
      <template v-if="publish">
        <h2 class="section-title">Top 3</h2>
        <div class="podium">
          <div
            v-for="(bakery, index) in resultatAPI.slice(0, 3)"
            :key="bakery.id"
            class="podium-card"
            :class="'podium-' + (index + 1)"
          >
            <div class="podium-rank">
              {{ index === 0 ? "🥇" : index === 1 ? "🥈" : "🥉" }}
            </div>
            <div class="podium-name">{{ bakery.company_name }}</div>
            <div class="podium-score">
              {{ Number(bakery.moyenne).toFixed(1) }} / 5
            </div>
            <div class="podium-details">
              <span>Accueil: {{ Number(bakery.note_w).toFixed(1) }}</span>
              <span>Présentation: {{ Number(bakery.note_s).toFixed(1) }}</span>
              <span>Qualité: {{ Number(bakery.note_p).toFixed(1) }}</span>
            </div>
          </div>
        </div>

        <div v-if="resultatBySiret" class="mes-resultats">
          <h2 class="section-title">Vos résultats</h2>
          <div class="result-card">
            <div class="result-rank">
              <span class="rank-number">{{ classement }}</span>
              <span class="rank-label">ème</span>
            </div>
            <div class="result-info">
              <div class="result-name">{{ resultatBySiret.company_name }}</div>
              <div class="result-scores">
                <div class="score-item">
                  <span class="score-label">Accueil</span>
                  <div class="score-bar">
                    <div
                      class="score-fill"
                      :style="{
                        width: (Number(resultatBySiret.note_w) / 5) * 100 + '%',
                      }"
                    ></div>
                  </div>
                  <span class="score-value">{{
                    Number(resultatBySiret.note_w).toFixed(1)
                  }}</span>
                </div>
                <div class="score-item">
                  <span class="score-label">Présentation</span>
                  <div class="score-bar">
                    <div
                      class="score-fill"
                      :style="{
                        width: (Number(resultatBySiret.note_s) / 5) * 100 + '%',
                      }"
                    ></div>
                  </div>
                  <span class="score-value">{{
                    Number(resultatBySiret.note_s).toFixed(1)
                  }}</span>
                </div>
                <div class="score-item">
                  <span class="score-label">Qualité</span>
                  <div class="score-bar">
                    <div
                      class="score-fill"
                      :style="{
                        width: (Number(resultatBySiret.note_p) / 5) * 100 + '%',
                      }"
                    ></div>
                  </div>
                  <span class="score-value">{{
                    Number(resultatBySiret.note_p).toFixed(1)
                  }}</span>
                </div>
              </div>
            </div>
            <div class="result-moyenne">
              <span class="moyenne-value">{{
                Number(resultatBySiret.moyenne).toFixed(1)
              }}</span>
              <span class="moyenne-label">/ 5</span>
            </div>
          </div>
        </div>
      </template>
      <template v-else>
        <div class="message-attente">
          <span class="message-icon">⏳</span>
          <p>Le classement n'a pas encore été publié, revenez plus tard !</p>
        </div>
      </template>
    </template>
    <template v-if="isVisitor">
      <template v-if="publish">
        <h2 class="section-title">Top 3</h2>
        <div class="podium">
          <div
            v-for="(bakery, index) in resultatAPI.slice(0, 3)"
            :key="bakery.id"
            class="podium-card"
            :class="'podium-' + (index + 1)"
          >
            <div class="podium-rank">
              {{ index === 0 ? "🥇" : index === 1 ? "🥈" : "🥉" }}
            </div>
            <div class="podium-name">{{ bakery.company_name }}</div>
            <div class="podium-score">
              {{ Number(bakery.moyenne).toFixed(1) }} / 5
            </div>
            <div class="podium-details">
              <span>Accueil: {{ Number(bakery.note_w).toFixed(1) }}</span>
              <span>Présentation: {{ Number(bakery.note_s).toFixed(1) }}</span>
              <span>Qualité: {{ Number(bakery.note_p).toFixed(1) }}</span>
            </div>
          </div>
        </div>
      </template>
      <template v-else>
        <div class="message-attente">
          <span class="message-icon">⏳</span>
          <p>Le classement n'a pas encore été publié, revenez plus tard !</p>
        </div>
      </template>
    </template>
  </div>
</template>

<script setup>
import axios from "axios";
import { ref, computed, onMounted } from "vue";

const userData = JSON.parse(localStorage.getItem("user"));
const userRole = ref(userData?.role || null);
const isAdmin = computed(() => userRole.value === "ROLE_ADMIN");
const isBaker = computed(() => userRole.value === "ROLE_BAKER");
const isVisitor = computed(() => !isAdmin.value && !isBaker.value);
const siret = ref(localStorage.getItem("idBakery"));

const publish = ref(false);
const resultatAPI = ref(JSON.parse(localStorage.getItem("resultatData")) || []);
const resultatBySiret = ref(null);
const resultatContestParam = ref(null);
const isGenerated = ref(resultatAPI.value.length > 0);

onMounted(() => {
  getResultatBySiret();
  getContestParam();
});

const classement = computed(() => {
  const index = resultatAPI.value.findIndex(
    (b) => b.bakery_siret === siret.value,
  );
  return index !== -1 ? index + 1 : "N/A";
});

async function getContestParam() {
  try {
    const response = await axios.get("http://localhost:8000/api/contestParams");
    resultatContestParam.value = response.data;
    publish.value = response.data.isPublished;
  } catch (error) {
    console.error(error);
  }
}

async function published() {
  try {
    await axios.patch("http://localhost:8000/api/contestParams/publish");
    publish.value = !publish.value;
  } catch (error) {
    console.error(error);
  }
}

async function fetchGet() {
  try {
    const response = await axios.get("http://localhost:8000/api/evaluation");
    resultatAPI.value = response.data;
    isGenerated.value = true;
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
@import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap");

* {
  box-sizing: border-box;
}

.resultats-container {
  width: 90%;
  max-width: 1000px;
  margin: 40px auto;
  font-family: "DM Sans", sans-serif;
}

.header {
  text-align: center;
  margin-bottom: 40px;
}

.titre {
  font-family: "Playfair Display", serif;
  font-size: 42px;
  color: #1a1a2e;
  margin-bottom: 8px;
}

.sous-titre {
  color: #888;
  font-size: 16px;
}

.section-title {
  font-family: "Playfair Display", serif;
  font-size: 26px;
  color: #1a1a2e;
  margin: 40px 0 20px;
  text-align: center;
}

.table-wrapper {
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}

.table-resultats {
  width: 100%;
  border-collapse: collapse;
  background: white;
}

.table-resultats th {
  background: #1a1a2e;
  color: white;
  padding: 16px 20px;
  font-size: 13px;
  font-weight: 600;
  letter-spacing: 1px;
  text-transform: uppercase;
  text-align: left;
}

.table-resultats td {
  padding: 16px 20px;
  border-bottom: 1px solid #f0f0f0;
  color: #333;
}

.table-resultats tr:last-child td {
  border-bottom: none;
}
.table-resultats tr:hover td {
  background: #f8f8ff;
}
.rank-1 td {
  background: #fffbeb;
}
.rank-2 td {
  background: #f9fafb;
}
.rank-3 td {
  background: #fff7f3;
}

.badge {
  font-size: 22px;
}

.bakery-name {
  font-weight: 600;
  color: #1a1a2e;
}

.note {
  background: #f0f4ff;
  color: #2d6fb7;
  padding: 4px 10px;
  border-radius: 20px;
  font-weight: 600;
  font-size: 14px;
}

.note.finale {
  background: #1a1a2e;
  color: white;
  font-size: 15px;
}

.btn-group {
  display: flex;
  gap: 12px;
  margin-top: 24px;
  justify-content: center;
}

.podium {
  display: flex;
  gap: 16px;
  justify-content: center;
  flex-wrap: wrap;
}

.podium-card {
  background: white;
  border-radius: 20px;
  padding: 28px 24px;
  text-align: center;
  width: 220px;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
  transition: transform 0.2s;
}

.podium-card:hover {
  transform: translateY(-4px);
}
.podium-1 {
  border-top: 4px solid #f59e0b;
  order: 2;
}
.podium-2 {
  border-top: 4px solid #9ca3af;
  order: 1;
}
.podium-3 {
  border-top: 4px solid #b45309;
  order: 3;
}

.podium-rank {
  font-size: 40px;
  margin-bottom: 12px;
}

.podium-name {
  font-weight: 700;
  font-size: 16px;
  color: #1a1a2e;
  margin-bottom: 8px;
}

.podium-score {
  font-size: 24px;
  font-weight: 700;
  color: #2d6fb7;
  margin-bottom: 12px;
}

.podium-details {
  display: flex;
  flex-direction: column;
  gap: 4px;
  font-size: 12px;
  color: #888;
}

.result-card {
  background: white;
  border-radius: 20px;
  padding: 28px 32px;
  display: flex;
  align-items: center;
  gap: 32px;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
  border-left: 6px solid #2d6fb7;
}

.result-rank {
  text-align: center;
  min-width: 70px;
}

.rank-number {
  font-family: "Playfair Display", serif;
  font-size: 52px;
  color: #2d6fb7;
  line-height: 1;
}

.rank-label {
  font-size: 14px;
  color: #888;
}

.result-info {
  flex: 1;
}

.result-name {
  font-weight: 700;
  font-size: 20px;
  color: #1a1a2e;
  margin-bottom: 16px;
}

.result-scores {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.score-item {
  display: flex;
  align-items: center;
  gap: 12px;
}

.score-label {
  font-size: 13px;
  color: #888;
  width: 90px;
  text-align: right;
}

.score-bar {
  flex: 1;
  height: 8px;
  background: #f0f0f0;
  border-radius: 10px;
  overflow: hidden;
}

.score-fill {
  height: 100%;
  background: linear-gradient(90deg, #2d6fb7, #5b9bd5);
  border-radius: 10px;
  transition: width 0.8s ease;
}

.score-value {
  font-size: 13px;
  font-weight: 600;
  color: #2d6fb7;
  width: 28px;
}

.result-moyenne {
  text-align: center;
}

.moyenne-value {
  font-family: "Playfair Display", serif;
  font-size: 48px;
  color: #1a1a2e;
  line-height: 1;
}

.moyenne-label {
  display: block;
  font-size: 14px;
  color: #888;
}

/* MESSAGE ATTENTE */
.message-attente {
  text-align: center;
  padding: 60px 20px;
  background: white;
  border-radius: 20px;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
}

.message-icon {
  font-size: 48px;
  display: block;
  margin-bottom: 16px;
}
.message-attente p {
  font-size: 18px;
  color: #888;
}
</style>
