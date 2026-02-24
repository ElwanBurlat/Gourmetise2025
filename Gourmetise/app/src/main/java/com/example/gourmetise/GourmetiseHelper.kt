import android.content.Context
import android.database.sqlite.SQLiteDatabase
import android.database.sqlite.SQLiteOpenHelper
class GourmetiseHelper (context : Context)
    : SQLiteOpenHelper (context, "baseGourmetise.db", null, 3){
    override fun onCreate(db: SQLiteDatabase) {
        // création des tables de la base embarquée
        // création de la table CONCURRENT
        db.execSQL("CREATE TABLE Bakery ("
                + "siret TEXT NOT NULL PRIMARY KEY,"
                + "companyName TEXT NOT NULL,"
                + "phone TEXT NOT NULL,"
                + "adress TEXT NOT NULL,"
                + "postalcode REAL NOT NULL,"
                + "nameContact TEXT NOT NULL,"
                + "phoneContact TEXT NOT NULL,"
                + "description TEXT NOT NULL);");

        db.execSQL("CREATE TABLE Evaluation ("
                + "id INTEGER PRIMARY KEY AUTOINCREMENT,"
                + "code TEXT NOT NULL ,"
                + "welcome REAL NOT NULL,"
                + "shopPresentation REAL NOT NULL,"
                + "productQuality REAL NOT NULL);");

    }
    override fun onUpgrade(db: SQLiteDatabase, oldVersion: Int, newVersion: Int) {
        db.execSQL("DROP TABLE IF EXISTS Bakery;");
        db.execSQL("DROP TABLE IF EXISTS Evaluation;")
        onCreate(db);
    }
}
