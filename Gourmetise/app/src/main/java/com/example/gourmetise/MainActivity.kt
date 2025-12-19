package com.example.gourmetise

import GourmetiseDAO
import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.widget.Toast
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.foundation.Image
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.width
import androidx.compose.material3.BottomAppBar
import androidx.compose.material3.Button
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Surface
import androidx.compose.material3.Text
import androidx.compose.material3.TopAppBar
import androidx.compose.material3.TopAppBarDefaults.topAppBarColors
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.res.stringResource
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.example.gourmetise.Bakery
import com.example.gourmetise.ui.theme.GourmetiseTheme
import okhttp3.MediaType.Companion.toMediaTypeOrNull
import okhttp3.OkHttpClient
import okhttp3.Request
import okhttp3.RequestBody
import okhttp3.RequestBody.Companion.toRequestBody
import okhttp3.Response
import org.json.JSONArray
import org.json.JSONObject
import java.io.IOException
import androidx.compose.material3.Scaffold
import androidx.compose.material3.TextField
import androidx.compose.material3.TopAppBar
import androidx.compose.material3.TopAppBarDefaults
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import kotlin.jvm.java


class MainActivity : ComponentActivity() {
    @OptIn(ExperimentalMaterial3Api::class)
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContent {
            GourmetiseTheme {
                val context = LocalContext.current
                var bdd = GourmetiseDAO(context = context);
                val prefs = context.getSharedPreferences("app_prefs", Context.MODE_PRIVATE)
                var alreadyImported by remember {
                    mutableStateOf(prefs.getBoolean("import_done", false))
                }


                Scaffold(
                    topBar = {
                        TopAppBar(
                            colors = topAppBarColors(
                                containerColor = MaterialTheme.colorScheme.primaryContainer,
                                titleContentColor = MaterialTheme.colorScheme.primary,
                            ),
                            title = {
                                Text("GOURMETISE")
                            }
                        )
                    },
                    bottomBar = {
                        BottomAppBar(
                            containerColor = MaterialTheme.colorScheme.primaryContainer,
                            contentColor = MaterialTheme.colorScheme.primary,
                            content =
                                {
                                    Row(
                                        Modifier.fillMaxWidth(),
                                        horizontalArrangement = Arrangement.SpaceEvenly,
                                        verticalAlignment = Alignment.CenterVertically
                                    ) {
                                        // Bouton IMPORTER
                                        Button(
                                            onClick = {


                                                val clientHTTP = OkHttpClient()
                                                val request = Request.Builder()
                                                    .url("http://10.0.2.2:8000/api/bakery")
                                                    .build()
                                                // Exécuter la requête en asynchrone
                                                clientHTTP.newCall(request).enqueue(object : okhttp3.Callback {
                                                    override fun onFailure(call: okhttp3.Call, e: IOException) {


                                                        runOnUiThread {
                                                            Toast.makeText(context, "ECHEC IMPORT ! "+e.toString(),
                                                                Toast.LENGTH_SHORT).show()
                                                            Log.i("erreur", e.toString())
                                                        }
                                                    }
                                                    override fun onResponse(call: okhttp3.Call, response: Response) {
                                                        if (response.isSuccessful) {
                                                            // Récupérer et déserialiser le corps de la réponse
                                                            val flux = response.body!!.string()
                                                            Log.i("CodeHTTP", response.code.toString())
                                                            Log.i("REPONSE", flux)
                                                            val fluxJson = JSONArray(flux)
                                                            bdd.supprimerToutesLesBakery()
                                                            for (i in 0 until fluxJson.length()) {
                                                                val jsonObject: JSONObject = fluxJson.getJSONObject(i)
                                                                val b = Bakery()

                                                                b.siret = jsonObject.getString("siret")
                                                                b.companyName = jsonObject.getString("companyName")
                                                                b.phone = jsonObject.getString("phone")
                                                                b.adress = jsonObject.getString("adress")
                                                                b.postalcode = jsonObject.getDouble("postalcode").toFloat()
                                                                b.nameContact = jsonObject.getString("nameContact")
                                                                b.phoneContact = jsonObject.getString("phoneContact")
                                                                b.description = jsonObject.getString("description")
                                                                bdd.ajouterBakery(b)
                                                            }
                                                            prefs.edit().putBoolean("import_done", true).apply()
                                                            alreadyImported=true
                                                            runOnUiThread { Toast.makeText(context, "IMPORT REUSSI !",
                                                                Toast.LENGTH_SHORT).show()
                                                            }
                                                        } else {
                                                            if (response.code == 403) {
                                                                runOnUiThread {
                                                                    Toast.makeText(
                                                                        context,
                                                                        "Hors période d’évaluation",
                                                                        Toast.LENGTH_LONG
                                                                    ).show()
                                                                    Log.i("erreur", "403 - Hors période d’évaluation")
                                                                }
                                                                return
                                                            }

                                                            runOnUiThread { Toast.makeText(context, "ECHEC IMPORT !\n" +
                                                                    response.code.toString()+ " "
                                                                    +response.message, Toast.LENGTH_SHORT).show()
                                                                Log.i("erreur", response.toString())
                                                            }
                                                        }
                                                    }
                                                }) },
                                            enabled = !alreadyImported,
                                            modifier = Modifier
                                                .padding(12.dp)
                                                .width(120.dp)
                                        )
                                        {
                                            Text(if (alreadyImported) "DÉJÀ IMPORTÉ" else "IMPORTER")
                                        }

                                    }
                                }
                        )
                    },
                    modifier = Modifier.fillMaxSize()
                ) { innerPadding ->
                    com.example.gourmetise.AccueilUI(
                        modifier = Modifier.padding(innerPadding)
                    )
                    }
                }
            }

        }
    }

@Composable
fun AccueilUI(modifier: Modifier = Modifier) {
    var nomProf by remember { mutableStateOf("") }
    val context = LocalContext.current
    Column(
        verticalArrangement = Arrangement.Center,
        horizontalAlignment = Alignment.CenterHorizontally,
        modifier = modifier.fillMaxWidth(),
    ) {
        Image(
            painter = painterResource(R.drawable.logo),
            contentDescription = null,
            contentScale = ContentScale.Fit,
            alpha = 0.5F,
            modifier = modifier
                .padding(top = 20.dp)
                .align(alignment = Alignment.CenterHorizontally)
        )
        Button(
            onClick = {
                val bdd = GourmetiseDAO(context)

                if (bdd.nombreDeBakery() == 0) {
                    Toast.makeText(
                        context,
                        "Aucune boulangerie importée",
                        Toast.LENGTH_SHORT
                    ).show()
                } else {
                    val intent = Intent(context, touteLesBakery::class.java)
                    context.startActivity(intent)
                }
            },
            modifier = Modifier
                .padding(12.dp)
                .width(180.dp)
        ) {
            Text("VOIR LES BOULANGERIES")
        }




    }
}
@Preview(showBackground = true)
@Composable
fun AccueilPreview() {
    GourmetiseTheme {
        AccueilUI();
    }
}



