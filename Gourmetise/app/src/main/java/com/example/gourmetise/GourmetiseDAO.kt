import android.annotation.SuppressLint
import android.content.ContentValues
import android.content.Context
import android.database.sqlite.SQLiteDatabase
import com.example.gourmetise.Bakery
import com.example.gourmetise.Evaluation


class GourmetiseDAO (context : Context) {
    lateinit var maBase : SQLiteDatabase
    lateinit var monHelper : GourmetiseHelper
    init {
        monHelper = GourmetiseHelper(context);
        maBase = monHelper.writableDatabase;
    }
    fun ajouterEvaluation(uneEvaluation:Evaluation,siret:String){
        val e = ContentValues()

        e.put("code", uneEvaluation.code)
        e.put("siret",siret)
        e.put("welcome", uneEvaluation.welcome)
        e.put("shopPresentation", uneEvaluation.shopPresentation)
        e.put("productQuality", uneEvaluation.productQuality)

        maBase.insert("Evaluation", null, e)

    }

    fun verifierCode(code:String): Boolean{
        val cursor = maBase.rawQuery("SELECT 1 FROM Evaluation WHERE code= ?",arrayOf(code))
        val existe = cursor.moveToFirst()
        return existe
    }

    fun verifierVote(siret: String): Boolean{
        val cursor =maBase.rawQuery("SELECT 1 FROM Evaluation WHERE siret=?",arrayOf(siret))
        val existe = cursor.moveToFirst()
        return existe
    }

    fun toutesLesEvaluations(): List<Evaluation> {
        val liste = mutableListOf<Evaluation>()
        val cursor = maBase.rawQuery("SELECT * FROM Evaluation", null)
        while (cursor.moveToNext()) {
            val e = Evaluation()
            e.code = cursor.getString(cursor.getColumnIndexOrThrow("code"))
            e.welcome = cursor.getFloat(cursor.getColumnIndexOrThrow("welcome"))
            e.shopPresentation = cursor.getFloat(cursor.getColumnIndexOrThrow("shopPresentation"))
            e.productQuality = cursor.getFloat(cursor.getColumnIndexOrThrow("productQuality"))
            e.bakery_id = cursor.getString(cursor.getColumnIndexOrThrow("siret"))
            liste.add(e)
        }
        cursor.close()
        return liste
    }

    fun nombreDeEvaluation(): Int {
        val cursor = maBase.rawQuery("SELECT COUNT(*) FROM Evaluation",null )
        cursor.moveToFirst()
        val count = cursor.getInt(0)
        cursor.close()
        return count
    }

    fun supprimerToutesLesBakery() {
        maBase.delete("Bakery", null, null)
    }

    fun nombreDeBakery(): Int {
        val cursor = maBase.rawQuery("SELECT COUNT(*) FROM Bakery",null )
        cursor.moveToFirst()
        val count = cursor.getInt(0)
        cursor.close()
        return count
    }

    fun ajouterBakery(uneBakery: Bakery) {

        val v = ContentValues()

        v.put("siret", uneBakery.siret)
        v.put("companyName", uneBakery.companyName)
        v.put("phone", uneBakery.phone)
        v.put("adress", uneBakery.adress)
        v.put("postalcode", uneBakery.postalcode)
        v.put("nameContact", uneBakery.nameContact)
        v.put("phoneContact", uneBakery.phoneContact)
        v.put("description", uneBakery.description)

        maBase.insert("Bakery", null, v)
    }



    @SuppressLint("Range")
    fun toutesLesBakery(): MutableList<Bakery> {

        val lesBakery = mutableListOf<Bakery>()

        val curseur = maBase.rawQuery(
            "SELECT siret, companyName, phone, adress, postalcode, nameContact, phoneContact, description FROM Bakery",
            arrayOf()
        )

        curseur.moveToFirst()
        while (!curseur.isAfterLast) {

            val siret = curseur.getString(curseur.getColumnIndex("siret"))
            val companyName = curseur.getString(curseur.getColumnIndex("companyName"))
            val phone = curseur.getString(curseur.getColumnIndex("phone"))
            val adress = curseur.getString(curseur.getColumnIndex("adress"))
            val postalcode = curseur.getFloat(curseur.getColumnIndex("postalcode"))
            val nameContact = curseur.getString(curseur.getColumnIndex("nameContact"))
            val phoneContact = curseur.getString(curseur.getColumnIndex("phoneContact"))
            val description = curseur.getString(curseur.getColumnIndex("description"))

            val b = Bakery()
            b.siret = siret
            b.companyName = companyName
            b.phone = phone
            b.adress = adress
            b.postalcode = postalcode
            b.nameContact = nameContact
            b.phoneContact = phoneContact
            b.description = description

            lesBakery.add(b)

            curseur.moveToNext()
        }

        curseur.close()
        return lesBakery
    }

}

class Evaluation {
}