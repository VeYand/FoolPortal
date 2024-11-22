import {createSlice, PayloadAction} from '@reduxjs/toolkit'
import {UserData} from 'shared/types'
import {USER_ROLE} from '../../../shared/types/types'


type UserEntitySlice = {
	user: UserData,
	initialized: boolean,
}

const emptyUser: UserEntitySlice = {
	user: {
		userId: '',
		firstName: '',
		lastName: '',
		role: USER_ROLE.STUDENT,
		email: '',
		groupIds: [],
	},
	initialized: false,
}

const initialState: UserEntitySlice = emptyUser

const userEntitySlice = createSlice({
	name: 'userEntity',
	initialState,
	reducers: {
		setUser: (state: UserEntitySlice, action: PayloadAction<UserData>) => {
			state.user = action.payload
			state.initialized = true
		},
	},
})

export {
	userEntitySlice,
}