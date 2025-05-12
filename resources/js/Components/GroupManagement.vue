<template>
    <div class="container mx-auto p-4">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Group Management</h1>
      </div>
  
      <!-- Alerts -->
      <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ error }}
      </div>
      <div v-if="success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ success }}
      </div>
  
      <!-- Create Group Form -->
      <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Create New Group</h2>
        <form @submit.prevent="createGroup">
          <div class="mb-4">
            <label for="name" class="block text-gray-700 font-semibold mb-2">Group Name</label>
            <input type="text" id="name" v-model="newGroup.name" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
          </div>
          <div class="mb-4">
            <label for="description" class="block text-gray-700 font-semibold mb-2">Description</label>
            <textarea id="description" v-model="newGroup.description" class="w-full px-3 py-2 border border-gray-300 rounded-md" rows="3"></textarea>
          </div>
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center">
            <span class="mr-2">Create Group</span> <i class="fas fa-plus-circle"></i>
          </button>
        </form>
      </div>
  
      <!-- Groups and Workers List -->
      <div class="flex flex-col md:flex-row gap-6">
        <!-- Groups List -->
        <div class="bg-white shadow-md rounded-lg p-6 md:w-1/3">
          <h2 class="text-xl font-semibold mb-4">Your Groups</h2>
          <div v-if="!groups || groups.length === 0" class="text-gray-500">No groups created yet.</div>
          <ul v-else class="divide-y divide-gray-200">
            <li v-for="group in groups" :key="group.id" class="py-3">
              <div class="flex justify-between items-center">
                <button 
                  @click="selectGroup(group)" 
                  class="text-left block flex-grow"
                  :class="{'font-semibold': selectedGroup && selectedGroup.id === group.id}"
                >
                  {{ group.name }}
                  <span class="text-sm text-gray-500 block">{{ group.workers ? group.workers.length : 0 }} workers</span>
                </button>
                <div class="flex space-x-2">
                  <button @click="openEditModal(group)" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button @click="deleteGroup(group.id)" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </div>
              </div>
            </li>
          </ul>
        </div>
  
        <!-- Group Details -->
        <div class="bg-white shadow-md rounded-lg p-6 md:w-2/3">
          <div v-if="!selectedGroup">
            <h2 class="text-xl font-semibold mb-4">Group Details</h2>
            <p class="text-gray-500">Select a group to view details</p>
          </div>
          
          <div v-else>
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-xl font-semibold">{{ selectedGroup.name }}</h2>
              <button 
                @click="openAddWorkerModal" 
                class="bg-green-600 text-white px-3 py-1 rounded-md hover:bg-green-700 flex items-center text-sm"
              >
                <span class="mr-1">Add Worker</span> <i class="fas fa-user-plus"></i>
              </button>
            </div>
            
            <p class="text-gray-700 mb-4">{{ selectedGroup.description }}</p>
            
            <h3 class="font-semibold text-lg mb-2">Workers in this group</h3>
            <div v-if="!selectedGroup.workers || selectedGroup.workers.length === 0" class="text-gray-500">
              No workers in this group.
            </div>
            <ul v-else class="divide-y divide-gray-200">
              <li v-for="worker in selectedGroup.workers" :key="worker.id" class="py-3 flex justify-between items-center">
                <div>
                  <span class="font-medium">{{ worker.first_name }} {{ worker.last_name }}</span>
                  <span class="text-sm text-gray-500 block">{{ worker.email }}</span>
                </div>
                <button 
                  @click="removeWorkerFromGroup(worker.id)" 
                  class="text-red-600 hover:text-red-800"
                  title="Remove from group"
                >
                  <i class="fas fa-user-minus"></i>
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>
  
      <!-- Add Worker Modal -->
      <div v-if="showAddWorkerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Add Worker to "{{ selectedGroup.name }}"</h3>
            <button @click="showAddWorkerModal = false" class="text-gray-500 hover:text-gray-700">
              <i class="fas fa-times"></i>
            </button>
          </div>
          
          <div v-if="!availableWorkers || availableWorkers.length === 0" class="text-gray-500 mb-4">
            All workers are already in this group or you don't have any workers.
          </div>
          
          <ul v-else class="divide-y divide-gray-200 mb-4 max-h-60 overflow-y-auto">
            <li v-for="worker in availableWorkers" :key="worker.id" class="py-3">
              <div class="flex justify-between items-center">
                <div>
                  <span class="font-medium">{{ worker.first_name }} {{ worker.last_name }}</span>
                  <span class="text-sm text-gray-500 block">{{ worker.email }}</span>
                </div>
                <button 
                  @click="addWorkerToGroup(worker.id)" 
                  class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 text-sm"
                >
                  Add
                </button>
              </div>
            </li>
          </ul>
          
          <div class="flex justify-end">
            <button @click="showAddWorkerModal = false" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400">
              Close
            </button>
          </div>
        </div>
      </div>
  
      <!-- Edit Group Modal -->
      <div v-if="showEditGroupModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Edit Group</h3>
            <button @click="showEditGroupModal = false" class="text-gray-500 hover:text-gray-700">
              <i class="fas fa-times"></i>
            </button>
          </div>
          
          <form @submit.prevent="updateGroup">
            <div class="mb-4">
              <label for="edit-name" class="block text-gray-700 font-semibold mb-2">Group Name</label>
              <input type="text" id="edit-name" v-model="editGroup.name" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
              <label for="edit-description" class="block text-gray-700 font-semibold mb-2">Description</label>
              <textarea id="edit-description" v-model="editGroup.description" class="w-full px-3 py-2 border border-gray-300 rounded-md" rows="3"></textarea>
            </div>
            <div class="flex justify-end gap-2">
              <button type="button" @click="showEditGroupModal = false" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400">
                Cancel
              </button>
              <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Update
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        groups: [],  // Initialize as an empty array
        workers: [],
        newGroup: {
          name: '',
          description: ''
        },
        selectedGroup: null,
        showAddWorkerModal: false,
        showEditGroupModal: false,
        editGroup: {
          id: null,
          name: '',
          description: ''
        },
        error: '',
        success: ''
      }
    },
    
    computed: {
      availableWorkers() {
        if (!this.selectedGroup || !this.selectedGroup.workers || !this.workers) return [];
        
        // Filter out workers that are already in the selected group
        const groupWorkerIds = this.selectedGroup.workers.map(worker => worker.id);
        return this.workers.filter(worker => !groupWorkerIds.includes(worker.id));
      }
    },
    
    mounted() {
      this.fetchGroups();
      this.fetchWorkers();
    },
    
    methods: {
      async fetchGroups() {
        try {
          const response = await axios.get('/groups');
          this.groups = response.data.groups || [];  // Provide fallback empty array
        } catch (error) {
          this.error = 'Failed to fetch groups';
          console.error('Error fetching groups:', error);
          setTimeout(() => this.error = '', 3000);
        }
      },
      
      async fetchWorkers() {
        try {
          const response = await axios.get('/workers');
          this.workers = response.data.workers || [];  // Provide fallback empty array
        } catch (error) {
          this.error = 'Failed to fetch workers';
          console.error('Error fetching workers:', error);
          setTimeout(() => this.error = '', 3000);
        }
      },
      
      async createGroup() {
        try {
          const response = await axios.post('/groups', this.newGroup);
          if (!this.groups) {
            this.groups = [];  // Ensure groups is initialized if undefined
          }
          const newGroup = response.data.group;
          if (newGroup) {
            this.groups.push(newGroup);
            this.newGroup = { name: '', description: '' };
            this.success = 'Group created successfully';
            setTimeout(() => this.success = '', 3000);
          } else {
            throw new Error('Invalid response from server');
          }
        } catch (error) {
          this.error = 'Failed to create group';
          console.error('Error creating group:', error);
          setTimeout(() => this.error = '', 3000);
        }
      },
      
      selectGroup(group) {
        if (group && group.id) {
          this.fetchGroupDetails(group.id);
        }
      },
      
      async fetchGroupDetails(groupId) {
        if (!groupId) return;
        
        try {
          const response = await axios.get(`/groups/${groupId}`);
          this.selectedGroup = response.data.group;
          if (!this.selectedGroup.workers) {
            this.selectedGroup.workers = [];  // Ensure workers array exists
          }
        } catch (error) {
          this.error = 'Failed to fetch group details';
          console.error('Error fetching group details:', error);
          setTimeout(() => this.error = '', 3000);
        }
      },
      
      openAddWorkerModal() {
        if (this.selectedGroup) {
          this.showAddWorkerModal = true;
        }
      },
      
      openEditModal(group) {
        if (!group) return;
        
        this.editGroup = {
          id: group.id,
          name: group.name,
          description: group.description || ''
        };
        this.showEditGroupModal = true;
      },
      
      async updateGroup() {
        if (!this.editGroup.id) return;
        
        try {
          const response = await axios.put(`/groups/${this.editGroup.id}`, {
            name: this.editGroup.name,
            description: this.editGroup.description
          });
          
          // Update local data
          const updatedGroup = response.data.group;
          if (updatedGroup) {
            const index = this.groups.findIndex(g => g.id === this.editGroup.id);
            if (index !== -1) {
              this.groups[index] = updatedGroup;
            }
            
            // Update selected group if it's the one being edited
            if (this.selectedGroup && this.selectedGroup.id === this.editGroup.id) {
              this.selectedGroup = updatedGroup;
            }
          }
          
          this.showEditGroupModal = false;
          this.success = 'Group updated successfully';
          setTimeout(() => this.success = '', 3000);
        } catch (error) {
          this.error = 'Failed to update group';
          console.error('Error updating group:', error);
          setTimeout(() => this.error = '', 3000);
        }
      },
      
      async deleteGroup(groupId) {
        if (!groupId) return;
        if (!confirm('Are you sure you want to delete this group?')) return;
        
        try {
          await axios.delete(`/groups/${groupId}`);
          this.groups = this.groups.filter(group => group.id !== groupId);
          if (this.selectedGroup && this.selectedGroup.id === groupId) {
            this.selectedGroup = null;
          }
          this.success = 'Group deleted successfully';
          setTimeout(() => this.success = '', 3000);
        } catch (error) {
          this.error = 'Failed to delete group';
          console.error('Error deleting group:', error);
          setTimeout(() => this.error = '', 3000);
        }
      },
      
      async addWorkerToGroup(workerId) {
        if (!workerId || !this.selectedGroup || !this.selectedGroup.id) return;
        
        try {
          await axios.post('/groups/add-worker', {
            group_id: this.selectedGroup.id,
            worker_id: workerId
          });
          
          // Refresh group details to show updated worker list
          await this.fetchGroupDetails(this.selectedGroup.id);
          this.showAddWorkerModal = false;
          this.success = 'Worker added to group successfully';
          setTimeout(() => this.success = '', 3000);
        } catch (error) {
          this.error = 'Failed to add worker to group';
          console.error('Error adding worker to group:', error);
          setTimeout(() => this.error = '', 3000);
        }
      },
      
      async removeWorkerFromGroup(workerId) {
        if (!workerId || !this.selectedGroup || !this.selectedGroup.id) return;
        if (!confirm('Are you sure you want to remove this worker from the group?')) return;
        
        try {
          await axios.post('/groups/remove-worker', {
            group_id: this.selectedGroup.id,
            worker_id: workerId
          });
          
          // Refresh group details to show updated worker list
          await this.fetchGroupDetails(this.selectedGroup.id);
          this.success = 'Worker removed from group successfully';
          setTimeout(() => this.success = '', 3000);
        } catch (error) {
          this.error = 'Failed to remove worker from group';
          console.error('Error removing worker from group:', error);
          setTimeout(() => this.error = '', 3000);
        }
      }
    }
  }
  </script>