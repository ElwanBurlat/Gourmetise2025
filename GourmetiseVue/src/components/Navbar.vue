<template>
  <v-app-bar app color="primary" dark>
    <!-- Logo + Titre -->
    <v-col class="d-flex align-center" cols="auto">
      <img src="../assets/logo.png" alt="Logo Gourmetise" class="logo" />
    </v-col>
    <v-app-bar-title>Gourmetise - Concours</v-app-bar-title>

    <v-spacer></v-spacer>

    <v-btn>
      <router-link to="/" class="router-link">Accueil</router-link>
    </v-btn>
    <v-btn>
      <router-link to="/results" class="router-link">Résultats</router-link>
    </v-btn>
    <v-btn v-if="user && user.role === 'ROLE_ADMIN'">
      <router-link to="/ContestParam" class="router-link"
        >Paramètres concours</router-link
      >
    </v-btn>

    <v-divider vertical class="mx-2"></v-divider>

    <div v-if="user" class="d-flex align-center">
      <v-btn v-if="user.role === 'ROLE_BAKER' && !hasBakery">
        <router-link to="/sign-inBaker" class="router-link">
          Inscrire ma boulangerie
        </router-link>
      </v-btn>
      <v-btn variant="outlined" class="ml-2 logout-btn" @click="logout">
        Se déconnecter
      </v-btn>
    </div>

    <div v-else class="d-flex align-center">
      <v-btn>
        <router-link to="/sign-in" class="router-link">S'inscrire</router-link>
      </v-btn>
      <v-btn variant="outlined" class="ml-2">
        <router-link to="/connection" class="router-link"
          >Connexion</router-link
        >
      </v-btn>
    </div>
  </v-app-bar>
</template>

<script setup>
import { useRouter } from "vue-router";
import axios from "axios";
import { ref, onMounted, onUnmounted } from "vue";
const router = useRouter();
const user = ref(JSON.parse(localStorage.getItem("user")));
const hasBakery = ref(false);

const INACTIVITY_LIMIT = 30 * 60 * 1000;
let inactivityTimer = null;

const resetTimer = () => {
  clearTimeout(inactivityTimer);
  inactivityTimer = setTimeout(() => {
    if (user.value) {
      localStorage.removeItem("user");
      user.value = null;
      router.push("/connection");
    }
  }, INACTIVITY_LIMIT);
};

onMounted(() => {
  window.addEventListener("mousemove", resetTimer);
  window.addEventListener("keydown", resetTimer);
  window.addEventListener("click", resetTimer);
  window.addEventListener("scroll", resetTimer);

  resetTimer();
});

onUnmounted(() => {
  window.removeEventListener("mousemove", resetTimer);
  window.removeEventListener("keydown", resetTimer);
  window.removeEventListener("click", resetTimer);
  window.removeEventListener("scroll", resetTimer);
  clearTimeout(inactivityTimer);
});
onMounted(async () => {
  console.log("id user :", user.value?.id);
  if (user.value?.role === "ROLE_BAKER") {
    try {
      const response = await axios.get(
        `http://localhost:8000/api/bakery/user/${user.value.id}`,
      );
      hasBakery.value = response.data.exists;
    } catch {
      hasBakery.value = false;
    }
  }
});

const logout = () => {
  localStorage.removeItem("user");
  user.value = null;
  router.push("/");
};
</script>

<style scoped>
.router-link {
  color: white;
  text-decoration: none;
}

.logout-btn {
  border-color: white;
  color: white;
}

img {
  max-height: 65px;
}
</style>
