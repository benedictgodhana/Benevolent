<template>
    <Head title="Chat" />
    <AdminLayout>
      <v-container width="100%" style="max-width: 1600px;">
        <v-row>
          <!-- Conversations List Section -->
          <v-col cols="12">
  <v-card>
    <!-- Toolbar for Inbox and Compose Button -->


    <v-card-text>
      <v-row>
        <!-- Inbox List -->
        <v-col cols="12" md="4">
            <v-toolbar style="background-color: darkblue; color: white;">
      <v-toolbar-title class="white--text">Inbox</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-tooltip bottom>
        <template v-slot:activator="{ props }">
          <v-btn
            v-bind="props"
            v-on="on"
            @click="showComposeDialog = true"
            color="primary"
            dark
          >
            <v-icon color="orange" size="25">mdi-chat-plus</v-icon>
          </v-btn>
        </template>
        <span>Start a New Conversation</span>
      </v-tooltip>
    </v-toolbar>
          <v-list>
            <v-list-item
              v-for="(conversation, index) in conversations"
              :key="index"
              @click="selectConversation(conversation)"
              :class="{ 'active-item': selectedConversation === conversation }"
            >
              <v-list-item-content class="conversation-item">
                <v-list-item-title>
                  <v-avatar class="avatar-wrapper">
                    <img
                      :src="getUserProfilePic(conversation.user)"
                      alt="User Avatar"
                      class="avatar-img"
                    />
                  </v-avatar>
                </v-list-item-title>
                <v-list-item-title>
                  <span
                    class="user-name"
                    style="margin-left: 40px; font-weight: 900;"
                  >
                    {{ conversation.user }}
                  </span>
                </v-list-item-title>
              </v-list-item-content>
              <v-list-item-subtitle style="margin-left: 70px">
                {{ conversation.lastMessage }}
              </v-list-item-subtitle>
            </v-list-item>
          </v-list>
        </v-col>

        <!-- Chat Messages Section -->
        <v-col cols="12" md="8">
          <v-card elevation="0">
            <v-card-title style="background-color:white ; color: green;">
              <v-btn icon @click="selectedConversation = null" style="color: black;" color="transparent" elevation="0">
                <v-icon color="orange">mdi-arrow-left</v-icon>
              </v-btn>
              <span class="ml-2" style="color: black;text-align: center">
                {{ selectedConversation ? selectedConversation.user : 'Select a Conversation to view' }}
              </span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
              <v-list class="chat-container">
                <v-list-item
                  v-for="(message, index) in selectedConversationMessages"
                  :key="index"
                  :class="message.sender === 'Me' ? 'message-item message-sent' : 'message-item message-received'"
                >
                  <v-card class="message-text">
                    <v-list-item-content>
                      <v-list-item-title>{{ message.text }}</v-list-item-title>
                      <v-list-item-subtitle class="message-date">{{ formatDate(message.date) }}</v-list-item-subtitle>
                    </v-list-item-content>
                  </v-card>
                </v-list-item>
              </v-list>
            </v-card-text>
            <v-card-actions style="background-color: darkgray; color: white;height: 83px;">
              <!-- Emoji Button -->
              <v-tooltip bottom>
                <template v-slot:activator="{ props }">
                  <v-btn
                    v-bind="props"
                    v-on="on"
                    icon
                    @click="toggleEmojiPicker"
                  >
                    <v-icon>mdi-emoticon-happy-outline</v-icon>
                  </v-btn>
                </template>
                <span>Emoji</span>
              </v-tooltip>
              <!-- Upload Button -->
              <v-tooltip bottom>
                <template v-slot:activator="{ on, props }">
                  <v-btn
                    v-bind="props"
                    v-on="on"
                    icon
                    @click="openFilePicker"
                  >
                    <v-icon>mdi-plus-box-outline</v-icon>
                  </v-btn>
                </template>
                <span>Upload Document/Picture</span>
              </v-tooltip>
              <!-- Voice Note Button -->
              <v-tooltip bottom>
                <template v-slot:activator="{ on, props }">
                  <v-btn
                    v-bind="props"
                    v-on="on"
                    icon
                    @click="startVoiceNote"
                  >
                    <v-icon>mdi-microphone-outline</v-icon>
                  </v-btn>
                </template>
                <span>Voice Note</span>
              </v-tooltip>
              <!-- Message Input -->
              <v-textarea
                v-model="newMessage"
                label="Type a message..."
                rows="1"
                variant="underlined"
                width="100%"
              ></v-textarea>
              <!-- Send Button -->
              <v-btn @click="sendMessage" color="primary">
                <v-icon>mdi-send</v-icon>
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</v-col>
</v-row>


        <!-- Compose Dialog -->
        <v-dialog v-model="showComposeDialog" max-width="600px">
          <v-card>
            <v-card-title style="background-color: orange;color:white;" class="text-center">
             New Conversation
            </v-card-title>
            <v-card-text>
              <v-form>
                <v-autocomplete
                  v-model="newConversationUser"
                  :items="users"
                  item-value="name"
                  item-title="name"
                  label="Select User"
                  variant="outlined"
                >
                  <template v-slot:prepend-item>
                    <v-list-item ripple @click="newConversationUser = ''">
                      <v-list-item-content>
                        <v-list-item-title>None</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                  </template>
                </v-autocomplete>
              </v-form>
            </v-card-text>
            <v-card-actions>
              <v-btn @click="showComposeDialog = false"  style="text-transform: capitalize;background-color: red;color:white">Cancel</v-btn>
              <v-btn @click="startConversation"  style="text-transform: capitalize;background-color: green;color:white">Start</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-container>
    </AdminLayout>
  </template>
<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { EmojiButton } from '@joeattardi/emoji-button';

// Props from Laravel controller
const props = defineProps({
  users: Array,
});

const conversations = ref([
  { user: 'John Doe', lastMessage: 'Hello, how are you?', messages: [
    { sender: 'John Doe', text: 'Hello, how are you?', date: '2024-08-01' },
    { sender: 'Me', text: 'I am good, thank you!', date: '2024-08-02' }
  ] },
  { user: 'Bazenga', lastMessage: 'Let\'s meet tomorrow.', messages: [
    { sender: 'Jane Smith', text: 'Let\'s meet tomorrow.', date: '2024-08-01' },
    { sender: 'Me', text: 'Sure, what time?', date: '2024-08-02' }
  ] },
]);

const selectedConversation = ref(null);
const showComposeDialog = ref(false);
const newConversationUser = ref('');
const newMessage = ref('');

const selectConversation = (conversation) => {
  selectedConversation.value = conversation;
};

const sendMessage = () => {
  if (selectedConversation.value && newMessage.value.trim()) {
    selectedConversation.value.messages.push({
      sender: 'Me',
      text: newMessage.value,
      date: new Date().toISOString()
    });
    newMessage.value = '';
  }
};

const startConversation = () => {
  if (newConversationUser.value.trim()) {
    conversations.value.push({
      user: newConversationUser.value,
      lastMessage: '',
      messages: []
    });
    newConversationUser.value = '';
    showComposeDialog.value = false;
  }
};

const selectedConversationMessages = computed(() => {
  return selectedConversation.value ? selectedConversation.value.messages : [];
});

const formatDate = (dateString) => {
  const date = new Date(dateString);
  const options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric' };
  return date.toLocaleDateString('en-US', options);
};

const getUserProfilePic = (userName) => {
  const user = props.users.find(user => user.name === userName);
  return user ? `/storage/${user.profile_pic}` : '/Images/male-avatar-icon.png';
};
</script>
<style scoped>
.conversation-item {
  display: flex;
  align-items: center;
  position: relative;
}

.avatar-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  margin-right: 10px;
}

.avatar-img {
  position: absolute;
  z-index: 0;
  border-radius: 50%;
  height: 50px;
  width: 50px;
}

.user-name {
  position: relative;
  z-index: 1;
  margin-left: 10px;
}

.message-item {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  margin-bottom: 10px;
}

.message-sent {
  justify-content: flex-end;
}

.message-received {
  justify-content: flex-start;
}

.message-text {
  max-width: 100%;
  word-wrap: break-word;
  padding: 10px;
}

.message-sent .message-text {
  background-color: lightblue;
  color: black;
  border-radius: 10px 0 10px;
}

.message-received .message-text {
  background-color: orange;
  color: black;
  border-radius: 10px 0 10px;

}

.message-date {
  font-size: 12px;
  color: grey;
  text-align: right;
}

.chat-container {
  max-height: 600px;
  overflow-y: auto;
}

.active-item {
  background-color: lightgrey;
}
</style>
