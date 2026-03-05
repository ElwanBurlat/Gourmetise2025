package com.example.gourmetise;

class Evaluation {
    var code: String = ""

    var bakery_id: String=""

    var welcome: Float = 0F

    var shopPresentation: Float = 0F

    var productQuality: Float = 0F

    override fun toString(): String {
        return "Evaluation(code='$code',bakery_id='$bakery_id' welcome=$welcome, shopPresentation=$shopPresentation, productQuality=$productQuality)"
    }
}
