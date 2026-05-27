<template>
  <v-snackbar
    v-model="snackbarGreen"
    color="success"
    timeout="2500"
    location="top"
  >
    Votre compte a été créé avec succès !
  </v-snackbar>
  <v-snackbar v-model="snackbarRed" color="error" timeout="2500" location="top">
    Erreur lors de l'inscription : {{ snackbarText }}
  </v-snackbar>

  <v-container>
    <v-row>
      <v-col>
        <h1>S'inscrire</h1>
        <v-text-field v-model="lastName" label="Last Name" />
        <v-text-field v-model="firstName" label="First Name" />
        <v-text-field v-model="email" label="Email" type="email" />
        <v-text-field
          v-model="password"
          label="Mots de passe"
          :type="showPassword ? 'text' : 'password'"
          :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
          @click:append-inner="showPassword = !showPassword"
        />
        <v-checkbox
          v-model="dataConsent"
          label="J'accepte le traitement de mes données."
        />
        <v-btn color="primary" @click="fetchPosts">Valider</v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { useRouter } from "vue-router";
import axios from "axios";
import { ref } from "vue";

const lastName = ref("");
const firstName = ref("");
const email = ref("");
const password = ref("");
const dataConsent = ref(false);
const showPassword = ref(false);

const snackbarGreen = ref(false);
const snackbarRed = ref(false);
const snackbarText = ref("");

const router = useRouter();

const fetchPosts = async () => {
  if (
    lastName.value === "" ||
    firstName.value === "" ||
    email.value === "" ||
    password.value === ""
  ) {
    return alert("Il manque des champs");
  }

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email.value)) {
    return alert("L'adresse email n'est pas valide");
  }

  if (!dataConsent.value) {
    return alert("Vous devez accepter le traitement des données.");
  }

  try {
    const response = await axios.post("http://localhost:8000/api/user", {
      lastName: lastName.value,
      firstName: firstName.value,
      email: email.value,
      passwordHash: password.value,
      role: "ROLE_BAKER",
    });

    localStorage.setItem(
      "user",
      JSON.stringify({
        id: response.data.id,
        lastName: lastName.value,
        firstName: firstName.value,
        email: email.value,
        role: "ROLE_BAKER",
      }),
    );

    snackbarGreen.value = true;
    setTimeout(() => {
      window.location.href = "/";
    }, 2500);
  } catch (error) {
    console.error("Erreur inscription :", error.response?.data?.message);
    snackbarText.value =
      error.response?.data?.message || "Une erreur est survenue.";
    snackbarRed.value = true;
  }
};
</script>

<style></style>
