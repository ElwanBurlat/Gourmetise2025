<template>
  <v-container class="mt-20">
    <v-row class="d-flex justify-center" dense>
      <!-- Voir les concours -->
      <v-col cols="20" md="10">
        <v-card elevation="4" rounded="xl">
          <v-card-title class="text-h5 font-weight-bold">
            Voir le concour
          </v-card-title>
          <v-card-text> Consultez et modifiez le concours. </v-card-text>
          <v-card-actions>
            <v-btn
              color="primary"
              variant="flat"
              block
              @click="goToSeeConcours()"
            >
              Voir le concour
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>

      <v-col cols="12" md="10" v-if="!concoursExiste">
        <v-card elevation="4" rounded="xl">
          <v-card-title class="text-h5 font-weight-bold">
            Créer un concours
          </v-card-title>
          <v-card-text> Ajoutez un nouveau concours facilement. </v-card-text>
          <v-card-actions>
            <v-btn
              color="success"
              variant="flat"
              block
              @click="goToCreateConcours()"
            >
              Créer un concours
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

const router = useRouter();
const concoursExiste = ref(false);

onMounted(async () => {
  try {
    const response = await axios.get("http://localhost:8000/api/contestParams");
    concoursExiste.value =
      response.data && Object.keys(response.data).length > 0;
  } catch (error) {
    console.error(error);
  }
});

function goToCreateConcours() {
  router.push("/CreateConcours");
}

function goToSeeConcours() {
  router.push("/SeeConcours");
}
</script>
