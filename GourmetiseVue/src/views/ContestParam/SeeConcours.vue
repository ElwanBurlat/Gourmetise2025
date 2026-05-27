<template>
    <v-container>
        <v-row>
            <v-col>
                 <v-card elevation="8" class="rounded-xl pa-4" style="min-width: 500px;">


                <v-card-title class="text-h5 d-flex align-center justify-space-between">
                    <span>{{ title }}</span>
                    <v-chip color="primary" class="text-white"

                    size="small"
                    >
                    {{ status }}
                    </v-chip>
                </v-card-title>

                <v-divider class="my-2"></v-divider>

                <v-card-text class="text-body-1">
                    {{ description }}
                </v-card-text>

                <v-card-actions class="d-flex justify-end">
                    <v-btn
                    color="primary"
                    variant="flat"
                    rounded="lg"
                    @click="editDialog = true"
                    >
                    Modifier
                    </v-btn>
                </v-card-actions>

                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="editDialog" max-width="500px">
        <v-card class="pa-4 rounded-xl">

            <v-card-title class="text-h6">
            Modifier le concours
            </v-card-title>

            <v-divider class="my-2"></v-divider>

            <v-card-text>
            <v-text-field label="Titre" v-model="title" />
            <v-textarea label="Description" v-model="description" />
            <v-select
                label="Statut"
                :items="statuses"
                item-title="label"
                item-value="value"
                v-model="status"
            />
            </v-card-text>

            <v-card-actions class="d-flex justify-end">
            <v-btn text @click="editDialog = false">Annuler</v-btn>
            <v-btn color="primary" @click="updateConcours">Enregistrer</v-btn>
            </v-card-actions>

        </v-card>
        </v-dialog>



    </v-container>


</template>
<script setup >
import { ref } from 'vue';
import { onMounted } from 'vue';
import axios from 'axios';
const title=ref("");
const description=ref("");
const status=ref("");
const editDialog = ref(false)
const statuses = [
  { label: 'Non ouvert', value: 'not_opened' },
  { label: 'Inscriptions en cours', value: 'registration' },
  { label: 'Évaluation en cours', value: 'evaluation' },
  { label: 'Terminé', value: 'finished' },
]
async function fetchGet() {
  try {
    const reponse = await axios.get('http://localhost:8000/api/contestParams');
    title.value = reponse.data.title;
    description.value = reponse.data.description;
    status.value = reponse.data.status;

    console.log("STATUS LABEL REÇU =", reponse.data.statusLabel);
} catch (error) {
    console.error(error)
  }
}

async function updateConcours() {
  try {
    await axios.put("http://localhost:8000/api/contestParams", {
      title: title.value,
      description: description.value,
      status: status.value
    })

    editDialog.value = false
    console.log("concours mis a jour")

  } catch (error) {
    console.error(error)
  }
}

onMounted(() => {
  fetchGet()
})


</script>