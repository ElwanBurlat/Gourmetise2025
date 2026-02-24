package com.example.gourmetise;

class Evaluation {
    var code: String = ""

    var welcome: Float = 0F

    var shopPresentation: Float = 0F

    var productQuality: Float = 0F

    override fun toString(): String {
        return "Evaluation(code='$code', welcome=$welcome, shopPresentation=$shopPresentation, productQuality=$productQuality)"
    }
}
