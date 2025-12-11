import android.annotation.SuppressLint
import android.content.ContentValues
import android.content.Context
import android.database.sqlite.SQLiteDatabase
import com.example.gourmetise.Bakery


class GourmetiseDAO (context : Context) {
    lateinit var maBase : SQLiteDatabase
    lateinit var monHelper : GourmetiseHelper
    init {
        monHelper = GourmetiseHelper(context);
        maBase = monHelper.writableDatabase;
    }

    fun supprimerToutesLesBakery() {
        maBase.delete("Bakery", null, null)
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

        // On sélectionne seulement les colonnes qu'on veut
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