<template>
<v-container class="mt-20">
  <v-row class="d-flex justify-center" dense>

    
    <v-col cols="20" md="10">
      <v-card elevation="4" rounded="xl">
        <v-card-title class="text-h5 font-weight-bold">
          Voir les concours
        </v-card-title>

        <v-card-text>
          Consultez l’ensemble des concours existants.
        </v-card-text>

        <v-card-actions>
          <v-btn
            color="primary"
            variant="flat"
            block
            @click="router.push('/concours')"
          >
            Voir les concours
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-col>

    
    <v-col cols="12" md="10">
      <v-card elevation="4" rounded="xl">
        <v-card-title class="text-h5 font-weight-bold">
          Créer un concours
        </v-card-title>

        <v-card-text>
          Ajoutez un nouveau concours facilement.
        </v-card-text>

        <v-card-actions>
          <v-btn
            color="success"
            variant="flat"
            block
            @click="router.push('/contestparams')"
          >
            Créer un concours
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-col>

  </v-row>
</v-container>
</template>
<script setup >





</script>