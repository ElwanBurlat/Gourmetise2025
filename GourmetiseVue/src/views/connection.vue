<template>
  <v-snackbar
    v-model="snackbarGreen"
    color="success"
    timeout="2500"
    location="top"
  >
    Connexion réussie !
  </v-snackbar>
  <v-snackbar v-model="snackbarRed" color="error" timeout="2500" location="top">
    {{ snackbarText }}
  </v-snackbar>

  <v-container>
    <v-row justify="center">
      <v-col cols="12" sm="8" md="5">
        <h1 class="mb-4">Se connecter</h1>

        <v-text-field v-model="email" label="Email" type="email" />

        <v-text-field
          v-model="password"
          label="Mot de passe"
          :type="showPassword ? 'text' : 'password'"
          :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
          @click:append-inner="showPassword = !showPassword"
        />

        <v-btn color="primary" block @click="login">Se connecter</v-btn>

        <p class="mt-4 text-center">
          Pas encore de compte ?
          <router-link to="/sign-in">S'inscrire</router-link>
        </p>
      </v-col>
    </v-row>
  </v-container>
</template>
<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

const router = useRouter();

const email = ref("");
const password = ref("");
const showPassword = ref(false);

const snackbarGreen = ref(false);
const snackbarRed = ref(false);
const snackbarText = ref("");

const login = async () => {
  if (email.value === "" || password.value === "") {
    return alert("Il manque des champs");
  }

  try {
    const response = await axios.post("http://localhost:8000/api/login", {
      email: email.value,
      password: password.value,
    });

    localStorage.setItem("user", JSON.stringify(response.data));

    snackbarGreen.value = true;
    setTimeout(() => {
      window.location.href = "/";
    }, 2500);
  } catch (error) {
    console.error("Erreur connexion :", error.response?.data);
    snackbarText.value =
      error.response?.data?.message || "Email ou mot de passe incorrect.";
    snackbarRed.value = true;
  }
};
</script>
<style scoped></style>
