package com.example.gourmetise
import GourmetiseDAO
import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.foundation.layout.Arrangement
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
import androidx.compose.material.icons.filled.LocationOn
import androidx.compose.material.icons.filled.Person
import androidx.compose.material.icons.filled.Phone
import androidx.compose.material3.Card
import androidx.compose.material3.CardDefaults
import androidx.compose.material3.Icon
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Surface
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.example.gourmetise.ui.theme.GourmetiseTheme
import com.example.gourmetise.ui.theme.PurpleGrey40


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
    var bdd = GourmetiseDAO(context = context);
    var lesBakery = bdd.toutesLesBakery()



    Column(
        verticalArrangement = Arrangement.Top,
        modifier = Modifier
            .fillMaxSize()
            .padding(top=50.dp,start=16.dp, end = 16.dp)

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
                ){
            lesBakery.forEach { bakery ->
                BakeryCard(bakery)
            }
        }
    }

}
@Composable
fun BakeryCard(bakery: Bakery) {

    Card(
        modifier = Modifier
            .padding(12.dp)
            .fillMaxWidth(),
        shape = RoundedCornerShape(20.dp),
        elevation = CardDefaults.cardElevation(8.dp)
    ) {

        Column(modifier = Modifier.padding(16.dp)) {

            // NOM DE LA BAKERY
            Text(
                text = bakery.companyName,
                fontSize = 22.sp,
                fontWeight = FontWeight.Bold,
                color = MaterialTheme.colorScheme.primary
            )

            Spacer(modifier = Modifier.height(8.dp))

            // SIRET
            Text(
                text = "SIRET : ${bakery.siret}",
                fontSize = 14.sp,
                color = MaterialTheme.colorScheme.secondary
            )

            Spacer(modifier = Modifier.height(12.dp))

            // ADRESSE
            Row(verticalAlignment = Alignment.CenterVertically) {
                Icon(
                    Icons.Default.LocationOn,
                    contentDescription = null,
                    tint = MaterialTheme.colorScheme.tertiary
                )
                Text(
                    text = "${bakery.adress}, ${bakery.postalcode}",
                    modifier = Modifier.padding(start = 6.dp),
                    fontSize = 16.sp
                )
            }

            Spacer(modifier = Modifier.height(8.dp))

            // PHONE BAKERY
            Row(verticalAlignment = Alignment.CenterVertically) {
                Icon(
                    Icons.Default.Phone,
                    contentDescription = null,
                    tint = MaterialTheme.colorScheme.tertiary
                )
                Text(
                    text = bakery.phone,
                    modifier = Modifier.padding(start = 6.dp),
                    fontSize = 16.sp
                )
            }

            Spacer(modifier = Modifier.height(8.dp))

            // CONTACT
            Row(verticalAlignment = Alignment.CenterVertically) {
                Icon(
                    Icons.Default.Person,
                    contentDescription = null,
                    tint = MaterialTheme.colorScheme.tertiary
                )
                Text(
                    text = "${bakery.nameContact} — ${bakery.phoneContact}",
                    modifier = Modifier.padding(start = 6.dp),
                    fontSize = 16.sp
                )
            }

            Spacer(modifier = Modifier.height(12.dp))

            // DESCRIPTION
            Text(
                text = bakery.description,
                fontSize = 14.sp,
                color = MaterialTheme.colorScheme.outline
            )
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
        BakeryCard(bakery)
    }
}

