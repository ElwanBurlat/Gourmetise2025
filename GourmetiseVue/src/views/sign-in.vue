<template>
    <v-snackbar
        v-model="snackbar"
        color="success"
        timeout="2500"
        location="top"
        >
        🎉 Votre boulangerie a été enregistrée !
    </v-snackbar>
    <v-container>
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
                    v-model="city"
                    label="Ville"
                />
                <v-text-field
                    v-model="postalcode"
                    label="Code Postal"
                />
                <v-select
                    clearable
                    label="Pays"
                    :items="['France']"
                    v-model="country"
                ></v-select>
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

                <v-btn color="primary" @click="fetchPosts">Valider</v-btn>
            </v-col>
        </v-row>
    </v-container>
</template>
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
const country =ref('')
const adress = ref('')
const router = useRouter()
const snackbar = ref(false)

const fetchPosts = async () => {
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
        country: country.value,
        nameContact: nameContact.value,
        phoneContact: phoneContact.value,
        description: description.value,
        bakeryUser: {
          email: "Baker1.martin@gourmetise.com"  
        }
      }
    )
     console.log('Données envoyées avec succès :', response.data)
    
    snackbar.value = true
    setTimeout(() => {
        router.push('/')
    }, 2500) 
   
  } catch (error) {
    console.error('Erreur lors de l’envoi :', error)
  }
}


</script>