package com.example.gourmetise

class Bakery() {

    var siret: String = ""
    var companyName: String = ""
    var phone: String = ""
    var adress: String = ""
    var postalcode: Float = 0F
    var nameContact: String = ""
    var phoneContact: String = ""
    var description: String = ""

    override fun toString(): String {
        return "Bakery(siret='$siret', companyName='$companyName', phone='$phone', adress='$adress', " +
                "postalCode=$postalcode, nameContact='$nameContact', phoneContact='$phoneContact', " +
                "description='$description')"
    }
}
