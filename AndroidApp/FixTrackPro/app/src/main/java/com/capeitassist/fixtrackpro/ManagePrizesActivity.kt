package com.capeitassist.fixtrackpro

import android.os.Bundle
import android.widget.Button
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView

class ManagePrizesActivity : AppCompatActivity() {
    private lateinit var rvPrizes: RecyclerView
    private lateinit var btnAddPrize: Button

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_manage_prizes)

        rvPrizes = findViewById(R.id.rvPrizes)
        btnAddPrize = findViewById(R.id.btnAddPrize)

        rvPrizes.layoutManager = LinearLayoutManager(this)

        btnAddPrize.setOnClickListener {
            Toast.makeText(this, "Add Prize Dialog would open here", Toast.LENGTH_SHORT).show()
        }
    }
}
