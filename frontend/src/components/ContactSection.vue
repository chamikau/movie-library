<template>
  <section id="contact" class="section contact">
    <div class="container contact-grid">
      <div class="form-side">
        <p class="eyebrow">
          HOW TO REACH US
        </p>
        <p class="contact-text">
          Lorem ipsum dolor sit amet, consetetur.
        </p>
        <div
          v-if="formMessage"
          :class="[
            'message',
            formSuccess ? 'success' : 'error'
          ]"
        >
          {{ formMessage }}
        </div>
        <form @submit.prevent="submitContact">
          <div class="form-row">
            <div class="field">
              <label>
                First Name *
              </label>
              <input
                v-model="form.first_name"
                type="text"
                maxlength="80"
              />
              <small>
                {{ errors.first_name }}
              </small>
            </div>
            <div class="field">
              <label>
                Last Name *
              </label>
              <input
                v-model="form.last_name"
                type="text"
                maxlength="80"
              />
              <small>
                {{ errors.last_name }}
              </small>
            </div>
          </div>
          <div class="field">
            <label>Email *</label>
            <input
              v-model="form.email"
              type="email"
              maxlength="160"
            />
            <small>
              {{ errors.email }}
            </small>
          </div>
          <div class="field">
            <label>Telephone</label>
            <input
              v-model="form.phone"
              type="tel"
              maxlength="30"
            />
            <small>
              {{ errors.phone }}
            </small>
          </div>
          <div>
            <label>
              Message *
            </label>
            <textarea
              v-model="form.comments"
              rows="5"
              maxlength="2000"
            ></textarea>
            <small>
              {{ errors.comments }}
            </small>
          </div>
          <p class="required-note">*required fields</p>
          <label class="terms">
            <input
              v-model="form.agreed"
              type="checkbox"
            />
            <span>
              I agree to the
              <a href="#" target="_blank" rel="noopener">Terms &amp; Conditions</a>
            </span>
          </label>
          <small class="terms-error">
            {{ errors.agreed }}
          </small>
          <button
            class="btn primary"
            type="submit"
            :disabled="submitting"
          >
            {{ submitting
              ? 'Sending...'
              : 'Submit'
            }}
          </button>
        </form>
      </div>
      <div class="map-side">
        <div class="map">
          <iframe
            title="eBEYONDS location"
            src="https://www.google.com/maps?q=eBEYONDS%20Sri%20Lanka&output=embed"
            loading="lazy"
          ></iframe>
        </div>
      </div>
    </div>
  </section>
</template>
<script setup>

import { reactive, ref } from 'vue'
const BACKEND =
  'http://localhost/ebeyonds-vue-php/backend/api'

const submitting = ref(false)
const formMessage = ref('')
const formSuccess = ref(false)

const form = reactive({
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  comments: '',
  agreed: false
})

const errors = reactive({
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  comments: '',
  agreed: ''
})

function validate() {

  Object.keys(errors).forEach(
    key => errors[key] = ''
  )

  if (!form.first_name.trim()) {
    errors.first_name =
      'First name is required.'
  }

  if (!form.last_name.trim()) {
    errors.last_name =
      'Last name is required.'
  }

  if (!form.email.trim()) {

    errors.email =
      'Email is required.'

  } else if (
    !/^[^\s@]+@[^\s@]+\.[^\s@]+$/
      .test(form.email)
  ) {

    errors.email =
      'Enter a valid email.'
  }

  if (
    form.phone &&
    !/^[0-9+() .-]{7,30}$/
      .test(form.phone)
  ) {

    errors.phone =
      'Enter a valid phone number.'
  }

  if (!form.comments.trim()) {

    errors.comments =
      'Message is required.'
  }

  if (!form.agreed) {

    errors.agreed =
      'Please agree to the Terms & Conditions.'
  }

  return !Object.values(errors).some(Boolean)
}

async function submitContact() {

  formMessage.value = ''
  formSuccess.value = false

  if (!validate()) {

    formMessage.value =
      'Please correct the highlighted fields.'

    return
  }

  submitting.value = true

  const body = new URLSearchParams({
    first_name: form.first_name,
    last_name: form.last_name,
    email: form.email,
    phone: form.phone,
    comments: form.comments
  })

  try {

    const response = await fetch(
      `${BACKEND}/contact.php`,
      {
        method: 'POST',
        headers: {
          'Content-Type':
            'application/x-www-form-urlencoded'
        },
        body
      }
    )

    const result =
      await response.json()

    if (
      !response.ok ||
      !result.success
    ) {

      throw new Error(
        result.message ||
        'Submission failed.'
      )
    }

    formSuccess.value = true

    formMessage.value =
      result.message

    Object.keys(form).forEach(
      key => form[key] = typeof form[key] === 'boolean' ? false : ''
    )

  } catch (error) {

    formMessage.value =
      error.message ||
      'Unable to submit form.'

  } finally {

    submitting.value = false
  }
}

</script>

<style scoped>
:root {
  box-sizing: border-box;
}

.contact {
  background-color: #0d0e10;
  padding: 64px 0;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 32px;
}

.contact-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 48px;
  align-items: start;
}

.eyebrow {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  font-size: 24px;
  font-weight: 800;
  color: #ffffff;
  margin: 0 0 8px;
  text-transform: none;
}

.contact-text {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  font-size: 13px;
  color: rgba(255, 255, 255, 0.55);
  margin: 0 0 24px;
}

.message {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  font-size: 13px;
  padding: 10px 14px;
  border-radius: 6px;
  margin-bottom: 16px;
}

.message.success {
  background: rgba(46, 160, 67, 0.15);
  color: #4caf50;
}

.message.error {
  background: rgba(211, 47, 47, 0.15);
  color: #f44336;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

.field {
  margin-bottom: 16px;
}

label {
  display: block;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  font-size: 12px;
  font-weight: 600;
  color: rgba(255, 255, 255, 0.7);
  margin-bottom: 6px;
}

input[type="text"],
input[type="email"],
input[type="tel"],
textarea {
  width: 100%;
  background-color: #1a1b1e;
  border: 1px solid rgba(255, 255, 255, 0.12);
  border-radius: 6px;
  color: #ffffff;
  font-size: 13px;
  padding: 10px 12px;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
@media (max-width: 640px) {
  input[type="text"],
  input[type="email"],
  input[type="tel"],
  textarea {
    font-size: 16px;
  }
}

textarea {
  resize: vertical;
}

input:focus,
textarea:focus {
  outline: none;
  border-color: #e0a020;
}

small {
  display: block;
  color: #f44336;
  font-size: 11px;
  margin-top: 4px;
  min-height: 14px;
}

.required-note {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  font-size: 11px;
  color: rgba(255, 255, 255, 0.4);
  margin: 0 0 16px;
}

.terms {
  display: flex;
  align-items: center;
  gap: 8px;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  font-size: 13px;
  color: rgba(255, 255, 255, 0.7);
  margin-bottom: 4px;
  cursor: pointer;
}

.terms input[type="checkbox"] {
  width: 15px;
  height: 15px;
  accent-color: #e0a020;
  flex-shrink: 0;
}

.terms a {
  color: #e0a020;
  text-decoration: underline;
}

.terms-error {
  margin-bottom: 12px;
}

.btn.primary {
  display: block;
  margin-left: auto;
  background-color: #e0a020;
  color: #16171a;
  border: none;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  font-size: 14px;
  font-weight: 700;
  letter-spacing: 0.03em;
  text-transform: uppercase;
  padding: 12px 32px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.15s ease;
}

.btn.primary:hover {
  background-color: #f0b030;
}

.btn.primary:disabled {
  opacity: 0.6;
  cursor: default;
}

.map-side {
  margin-top: 92px;
}

.map {
  width: 100%;
  height: 340px;
  border-radius: 8px;
  overflow: hidden;
}

.map iframe {
  width: 100%;
  height: 100%;
  border: none;
}

.map-link {
  display: inline-block;
  margin-top: 12px;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  font-size: 13px;
  color: #e0a020;
  text-decoration: none;
}

.map-link:hover {
  text-decoration: underline;
}
@media (max-width: 860px) {
  .contact {
    padding: 48px 0;
  }

  .contact-grid {
    grid-template-columns: 1fr;
    gap: 32px;
  }

  .form-row {
    grid-template-columns: 1fr;
    gap: 0;
  }

  .map-side {
    margin-top: 0;
  }

  .map {
    height: 260px;
  }

  .btn.primary {
    width: 100%;
    text-align: center;
    padding: 14px 0;
  }
}
@media (max-width: 420px) {
  .contact {
    padding: 36px 0;
  }

  .container {
    padding: 0 16px;
  }

  .eyebrow {
    font-size: 20px;
  }

  .contact-text {
    margin-bottom: 18px;
  }

  .field {
    margin-bottom: 12px;
  }

  .map {
    height: 220px;
  }

  .map-link {
    font-size: 12px;
  }
}
</style>