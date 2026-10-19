package com.capeitassist.fixtrackpro

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.google.android.material.appbar.MaterialToolbar

class SettingsActivity : AppCompatActivity() {

    private lateinit var fullNameEditText: EditText
    private lateinit var settingsEmailEditText: EditText
    private lateinit var newPasswordEditText: EditText
    private lateinit var saveChangesButton: Button
    private lateinit var helpSupportButton: Button
    private lateinit var logoutButton: Button

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_settings)

        val toolbar = findViewById<MaterialToolbar>(R.id.toolbar)
        setSupportActionBar(toolbar)
        supportActionBar?.setDisplayHomeAsUpEnabled(true)

        toolbar.setNavigationOnClickListener {
            finish()
        }

        // Bind views from activity_settings.xml
        fullNameEditText = findViewById(R.id.fullNameEditText)
        settingsEmailEditText = findViewById(R.id.settingsEmailEditText)
        newPasswordEditText = findViewById(R.id.newPasswordEditText)
        saveChangesButton = findViewById(R.id.saveChangesButton)
        helpSupportButton = findViewById(R.id.helpSupportButton)
        logoutButton = findViewById(R.id.logoutButton)

        // Load existing shared preferences data
        val sharedPref = getSharedPreferences("FixTrackPrefs", Context.MODE_PRIVATE)
        fullNameEditText.setText(sharedPref.getString("user_name", ""))
        settingsEmailEditText.setText(sharedPref.getString("user_email", ""))

        // Save Changes action
        saveChangesButton.setOnClickListener {
            val name = fullNameEditText.text.toString().trim()
            val email = settingsEmailEditText.text.toString().trim()

            sharedPref.edit()
                .putString("user_name", name)
                .putString("user_email", email)
                .apply()

            Toast.makeText(this, "Settings updated successfully!", Toast.LENGTH_SHORT).show()
        }

        // Help & Support button action
        helpSupportButton.setOnClickListener {
            startActivity(Intent(this, HelpActivity::class.java))
        }

        // Sign Out action
        logoutButton.setOnClickListener {
            sharedPref.edit().clear().apply()

            val intent = Intent(this, LoginActivity::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
            startActivity(intent)
            finish()
        }
    }
}