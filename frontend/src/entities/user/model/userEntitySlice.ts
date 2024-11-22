import {createSlice, PayloadAction} from '@reduxjs/toolkit'
import {UserData} from 'shared/types'


type UserEntitySlice = {
	user: UserData,
}

const emptyUser: UserEntitySlice = {
	user: {
		id: '',
	},
}

const initialState: UserEntitySlice = emptyUser

const userEntitySlice = createSlice({
	name: 'userEntity',
	initialState,
	reducers: {
		setUser: (state: UserEntitySlice, action: PayloadAction<UserData>) => {
			state.user = action.payload
		},
	},
})

export {
	userEntitySlice,
}