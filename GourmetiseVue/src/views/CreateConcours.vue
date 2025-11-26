<template>
    <v-container>
        <v-row>
            <v-col>
                <h1>Bienvenue sur la creation d'un concours</h1>
               <v-text-field
                    v-model="title"
                    label="Titre"
                />
                <v-text-field
                    v-model="description"
                    label="Description"
                />
                <v-select
                    v-model="status"
                    label="Pays"
                    :items="statuses"
                    item-title="label"
                    item-value="value"
                />
               <v-btn color="primary" @click="fetchPosts">Enregistrer</v-btn>
            </v-col>
        </v-row>
        
    </v-container>
</template>
<script setup>
import axios from 'axios';
import { ref } from 'vue'
const title = ref('')
const description = ref('')
const status = ref('')

const statuses  = [
  { label: 'Non ouvert', value: 'not_opened' },
  { label: 'Inscriptions en cours', value: 'registration' },
  { label: 'Évaluation en cours', value: 'evaluation' },
  { label: 'Terminé', value: 'finished' },
]
async function fetchPosts() {
  try{
    const reponse = await axios.post(
    'http://localhost:8000/api/contestParams',
    {
        title: title.value,
        description: description.value,
        status:status.value
    }
  );
  }catch(error){
    console.error(error)
    }  
}

async function fetchGet() {
  try{
    const reponse = await axios.get(
    'http://localhost:8000/api/contestParams'
   );
   console.log(reponse)
  }catch(error){
    console.error(error)
    }  
}

</script>