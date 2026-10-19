package com.capeitassist.fixtrackpro

import android.os.Bundle
import android.widget.Button
import androidx.appcompat.app.AppCompatActivity

class PrizesActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_prizes)

        findViewById<Button>(R.id.btnBack).setOnClickListener {
            finish()
        }
    }
}
