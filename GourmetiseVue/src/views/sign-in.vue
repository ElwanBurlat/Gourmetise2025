<template>
    <v-snackbar
        v-model="snackbarGreen"
        color="success"
        timeout="2500"
        location="top"
        >
         Votre boulangerie a été enregistrée 
    </v-snackbar>
    <v-snackbar
        v-model="snackbarRed"
        color="error"
        timeout="2500"
        location="top"
        >
         Erreur votre Boulagerie n'as pas été enregistrer  
    </v-snackbar>
    <v-container class="v-container">
        <v-row>
            <v-col>
                <h1>Inscrire sa boulangerie</h1>
               <v-text-field
                    v-model="siret"
                    label="Siret"
                />
                <v-text-field
                    v-model="companyName"
                    label="Nom de la boulangerie"
                />
                <v-text-field
                    v-model="phone"
                    label="Telephone"
                />
                <v-text-field
                    v-model="adress"
                    label="Adresse"
                />
                <v-text-field
                    v-model="postalcode"
                    label="Code Postal"
                    @input="findCity"
                />
                <v-select
                    v-model="city"
                    :items="cities"
                    label="Ville"
                    v-if="cities.length > 1"
                />

                <v-text-field
                    v-model="city"
                    label="Ville"
                    readonly
                    v-else
                />

                
                <v-text-field
                    v-model="nameContact"
                    label="Nom du Contact"
                />
                <v-text-field
                    v-model="phoneContact"
                    label="Telephone du contact"
                />
                <v-textarea 
                    v-model="description"
                    label="Description"
                ></v-textarea>
                <v-checkbox v-model="dataConsent" label="J’accepte le traitement de mes données."></v-checkbox>

                <v-btn color="primary" @click="fetchPosts">Valider</v-btn>
            </v-col>
        </v-row>
    </v-container>
</template>
<style>
.v-container {
    width: 100vw;
    height: 100vw;
}
</style>
<script setup>
import { useRouter } from 'vue-router'
import axios from 'axios';
import { ref } from 'vue'
const siret = ref('')
const companyName = ref('')
const phone = ref('')
const city = ref('')
const postalcode = ref('')
const nameContact = ref('')
const phoneContact = ref('')
const description = ref('')
const adress = ref('')
const router = useRouter()
const snackbarGreen = ref(false)
const snackbarRed= ref(false)
const cities= ref([])
const dataConsent = ref(false) 

const fetchPosts = async () => {
    if (
    siret.value=='' ||
    companyName.value=='' ||
    phone.value=='' ||
    adress.value=='' ||
    city.value=='' ||
    postalcode.value=='' ||
    nameContact.value=='' ||
    phoneContact.value=='' ||
    description.value==''
    ) 
    {
        return alert("Il manque des champs");
    }

    if(siret.value.length !== 15 && siret.value.length !== 9){
        return alert("Le numero siret n'est pas bon");
    }

    if (!dataConsent.value) {
        return alert("Vous devez accepter le traitement des données.");
    }
  
  
  
    try {
    const response = await axios.post(
      'http://localhost:8000/api/bakery',
      {
        siret: siret.value,
        companyName: companyName.value,
        phone: phone.value,
        adress: adress.value,
        city: city.value,
        postalcode: parseInt(postalcode.value, 5),
        nameContact: nameContact.value,
        phoneContact: phoneContact.value,
        description: description.value,
        bakeryUser: {
          email: "Baker1.martin@gourmetise.com"  
        }
      }
    )
     console.log('Données envoyées avec succès :', response.data)
    
    snackbarGreen.value = true
    setTimeout(() => {
        router.push('/')
    }, 2500) 
   
  } catch (error) {
    console.error('Erreur lors de l’envoi :', error)
    
    snackbarRed.value =true;
    setTimeout(() => {}, 2500) 
  }
}
const findCity = async () => {
  
    const response = await axios.get(`https://geo.api.gouv.fr/communes?codePostal=${postalcode.value}`)
    cities.value = response.data.map(v => v.nom)
    if (cities.value.length === 1) {
    city.value = cities.value[0]
    } else {
    city.value = ""
    }

  
}



</script>

