package com.example.gourmetise
import GourmetiseDAO
import android.os.Bundle
import android.os.Handler
import android.os.Looper
import android.util.Log
import android.widget.Toast
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.animation.AnimatedVisibility
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.KeyboardArrowDown
import androidx.compose.material.icons.filled.KeyboardArrowUp
import androidx.compose.material.icons.filled.LocationOn
import androidx.compose.material.icons.filled.Person
import androidx.compose.material.icons.filled.Phone
import androidx.compose.material3.Button
import androidx.compose.material3.Card
import androidx.compose.material3.CardDefaults
import androidx.compose.material3.Icon
import androidx.compose.material3.IconButton
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.RadioButton
import androidx.compose.material3.Surface
import androidx.compose.material3.Text
import androidx.compose.material3.TextField
import androidx.compose.runtime.Composable
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.example.gourmetise.ui.theme.GourmetiseTheme
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableFloatStateOf
import androidx.compose.runtime.setValue
import androidx.room.util.copy
import okhttp3.MediaType.Companion.toMediaType
import okhttp3.OkHttpClient
import okhttp3.Request
import okhttp3.RequestBody.Companion.toRequestBody
import okhttp3.Response
import org.json.JSONObject
import java.io.IOException


class touteLesBakery : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContent {
            GourmetiseTheme {
                Surface(modifier = Modifier.fillMaxSize()) {
                    ClassementUI()
                }
            }
        }
    }
}


@Composable
fun ClassementUI(modifier: Modifier = Modifier) {

    val context = LocalContext.current
    val bdd = GourmetiseDAO(context = context)
    val lesBakery = bdd.toutesLesBakery()

    var showPopup by remember { mutableStateOf(false) }
    var evaluation by remember { mutableStateOf(Evaluation()) }

    var welcome by remember { mutableFloatStateOf(3F) }
    var shopPresentation by remember { mutableFloatStateOf(3F) }
    var productQuality by remember { mutableFloatStateOf(3F) }

    var notesParBakery by remember { mutableStateOf<Map<String, Evaluation>>(emptyMap()) }

    Box(modifier = Modifier.fillMaxSize()) {

        Column(
            verticalArrangement = Arrangement.Top,
            modifier = Modifier
                .fillMaxSize()
                .padding(top = 50.dp, start = 16.dp, end = 16.dp)
        ) {

            Text(
                text = "Liste des boulangeries",
                style = MaterialTheme.typography.headlineMedium,
                fontWeight = FontWeight.Bold,
                modifier = Modifier.padding(bottom = 16.dp)
            )

            Column(
                modifier = Modifier
                    .verticalScroll(rememberScrollState())
                    .fillMaxWidth()
            ) {
                lesBakery.forEach { bakery ->
                    BakeryCard(
                        bakery = bakery,
                        onShowPopup = { codeSaisi, bakerySiret ->
                            evaluation.code = codeSaisi
                            evaluation.bakery_id = bakerySiret
                            showPopup = true
                        }
                    )
                }
            }
        }

        if (showPopup) {
            Box(
                modifier = Modifier
                    .fillMaxSize()
                    .background(Color.Black.copy(alpha = 0.4f)),
                contentAlignment = Alignment.Center
            ) {
                Card(
                    shape = RoundedCornerShape(20.dp),
                    modifier = Modifier.fillMaxWidth(0.9f)
                ) {
                    Column(
                        modifier = Modifier.padding(16.dp),
                        horizontalAlignment = Alignment.CenterHorizontally
                    ) {

                        Text(
                            text = "Évaluer la boulangerie",
                            fontWeight = FontWeight.Bold,
                            fontSize = 18.sp
                        )

                        Spacer(modifier = Modifier.height(16.dp))

                        Text("Accueil")
                        Row(
                            horizontalArrangement = Arrangement.SpaceEvenly,
                            modifier = Modifier.fillMaxWidth()
                        ) {
                            for (i in 1..5) {
                                Column(horizontalAlignment = Alignment.CenterHorizontally) {
                                    RadioButton(
                                        selected = welcome == i.toFloat(),
                                        onClick = { welcome = i.toFloat() }
                                    )
                                    Text(i.toString())
                                }
                            }
                        }

                        Spacer(modifier = Modifier.height(8.dp))

                        Text("Présentation du magasin")
                        Row(
                            horizontalArrangement = Arrangement.SpaceEvenly,
                            modifier = Modifier.fillMaxWidth()
                        ) {
                            for (i in 1..5) {
                                Column(horizontalAlignment = Alignment.CenterHorizontally) {
                                    RadioButton(
                                        selected = shopPresentation == i.toFloat(),
                                        onClick = { shopPresentation = i.toFloat() }
                                    )
                                    Text(i.toString())
                                }
                            }
                        }

                        Spacer(modifier = Modifier.height(8.dp))

                        Text("Qualité des produits")
                        Row(
                            horizontalArrangement = Arrangement.SpaceEvenly,
                            modifier = Modifier.fillMaxWidth()
                        ) {
                            for (i in 1..5) {
                                Column(horizontalAlignment = Alignment.CenterHorizontally) {
                                    RadioButton(
                                        selected = productQuality == i.toFloat(),
                                        onClick = { productQuality = i.toFloat() }
                                    )
                                    Text(i.toString())
                                }
                            }
                        }

                        Spacer(modifier = Modifier.height(16.dp))

                        Button(
                            onClick = {
                                evaluation.welcome = welcome
                                evaluation.productQuality = productQuality
                                evaluation.shopPresentation = shopPresentation
                                showPopup = false
                                bdd.ajouterEvaluation(evaluation,evaluation.bakery_id)
                                Toast.makeText(context, "ÉVALUATION RÉUSSIE", Toast.LENGTH_SHORT).show()

                            }
                        ) {
                            Text("Valider")
                        }
                    }
                }
            }
        }
    }
}

@Composable
fun BakeryCard(bakery: Bakery, onShowPopup: (String, String) -> Unit) {
    var expanded by remember { mutableStateOf(false) }
    var code by remember { mutableStateOf("") }
    val context = LocalContext.current
    val bdd = GourmetiseDAO(context = context)

    Card(
        modifier = Modifier
            .padding(12.dp)
            .fillMaxWidth(),
        shape = RoundedCornerShape(20.dp),
        elevation = CardDefaults.cardElevation(8.dp)
    ) {

        Column(modifier = Modifier.padding(16.dp)) {

            Text(
                text = bakery.companyName,
                fontSize = 22.sp,
                fontWeight = FontWeight.Bold,
                color = MaterialTheme.colorScheme.primary
            )

            Spacer(modifier = Modifier.height(8.dp))
            Spacer(modifier = Modifier.height(12.dp))

            Row(verticalAlignment = Alignment.CenterVertically) {
                Icon(Icons.Default.LocationOn, contentDescription = null, tint = MaterialTheme.colorScheme.tertiary)
                Text(text = "${bakery.adress}, ${bakery.postalcode}", modifier = Modifier.padding(start = 6.dp), fontSize = 16.sp)
            }

            Spacer(modifier = Modifier.height(8.dp))

            Row(verticalAlignment = Alignment.CenterVertically) {
                Icon(Icons.Default.Phone, contentDescription = null, tint = MaterialTheme.colorScheme.tertiary)
                Text(text = bakery.phone, modifier = Modifier.padding(start = 6.dp), fontSize = 16.sp)
            }

            Spacer(modifier = Modifier.height(8.dp))

            Row(verticalAlignment = Alignment.CenterVertically) {
                Icon(Icons.Default.Person, contentDescription = null, tint = MaterialTheme.colorScheme.tertiary)
                Text(text = "${bakery.nameContact} — ${bakery.phoneContact}", modifier = Modifier.padding(start = 6.dp), fontSize = 16.sp)
            }

            Spacer(modifier = Modifier.height(12.dp))

            Text(text = bakery.description, fontSize = 14.sp, color = MaterialTheme.colorScheme.outline)

            IconButton(
                modifier = Modifier.align(Alignment.CenterHorizontally),
                onClick = { expanded = !expanded }
            ) {
                Icon(
                    imageVector = if (expanded) Icons.Default.KeyboardArrowUp else Icons.Default.KeyboardArrowDown,
                    contentDescription = null
                )
            }

            AnimatedVisibility(visible = expanded) {
                Column(
                    modifier = Modifier
                        .fillMaxWidth()
                        .padding(top = 8.dp),
                    horizontalAlignment = Alignment.CenterHorizontally
                ) {

                    TextField(
                        value = code,
                        onValueChange = { code = it },
                        modifier = Modifier.fillMaxWidth(0.9f)
                    )

                    Spacer(modifier = Modifier.height(12.dp))

                    Button(
                        onClick = {
                           val codeExiste = bdd.verifierCode(code)
                            val evaluationExiste=bdd.verifierVote(bakery.siret)
                            if (evaluationExiste)
                            {
                                Toast.makeText(context, "Vous avez déjà évalué cette boulangerie !", Toast.LENGTH_SHORT).show()
                            }
                            else if (!codeExiste)
                            {
                                onShowPopup(code, bakery.siret)                            }
                            else
                            {
                                Toast.makeText(context, "Code déja utilisé", Toast.LENGTH_SHORT).show()
                            } },
                        enabled = code.isNotBlank()
                    ) {
                        Text("Entrer le code")
                    }
                }
            }
        }
    }
}


@Preview(showBackground = true)
@Composable
fun BakeryCardPreview() {
    GourmetiseTheme {
        val bakery = Bakery()
        bakery.siret = "12345678901234"
        bakery.companyName = "Boulangerie du Coin"
        bakery.phone = "0123456789"
        bakery.adress = "123 Rue du Pain"
        bakery.postalcode = 75001F
        bakery.nameContact = "Pierre Dupont"
        bakery.phoneContact = "0123456790"
        bakery.description = "Boulangerie artisanale"
        BakeryCard(
            bakery = bakery,
            onShowPopup = { _, _ -> }
        )
    }
}